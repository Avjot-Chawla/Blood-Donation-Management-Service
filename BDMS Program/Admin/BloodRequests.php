<?php
session_start();
$Username = $_SESSION["Username"];

// Connect Database
$conn = new mysqli('localhost', 'root', 'root', 'bdms_db');

// If connection not successfull
if ($conn->connect_error){
    die("Connection Failed: ".$conn->connect_error);
}
else{
    $stmt = "SELECT * FROM Blood_Request WHERE Status = 'Pending'";
    $result = mysqli_query($conn,$stmt);
}

// Check if Approve Button is clicked
if (isset($_POST["Approve"])){
    $PatientName = $_POST['Patient_Name'];
    $DateTime = $_POST['Date_Time'];
    $BloodGroup = $_POST['BloodGroup'];
    $UnitsRequested = $_POST['Units'];

    // Fetch Units available from database
    $stmt = "SELECT Units FROM Blood_bank WHERE (Blood_Type = '$BloodGroup')";
    $result = mysqli_query($conn,$stmt);
    $row = mysqli_fetch_array($result);

    $UnitsAvailable = $row["Units"];

    // Check if Requested Units is more than available Units
    if ($UnitsRequested <= $UnitsAvailable){
        // Update Status in the database
        $stmt = $conn->prepare("UPDATE Blood_Request SET Status = 'Approved' WHERE (Patient_Name = ? AND RequestDate = ?)");
        $stmt->bind_param("ss", $PatientName, $DateTime);
        $stmt->execute();

        // Update Units and Orders in the database
        $stmt = $conn->prepare("UPDATE Blood_Bank SET Units = Units - ?, Orders = Orders - ? WHERE Blood_Type = ?");
        $stmt->bind_param("iis", $UnitsRequested, $UnitsRequested, $BloodGroup);
        $stmt->execute();

        // Close Database Connection
        $stmt->close();
        $conn->close();

        // Display Message
        header("Location: BloodRequests.php");
        $_SESSION["Message"] = "Blood Request Approved.";
        exit;
    }
    // Display Error
    else{
        header("Location: BloodRequests.php");
        $_SESSION["Error"] = "Inadequate Units of Blood in stock.";
        exit;
    }
}

// Check if Reject Button is clicked
if (isset($_POST["Reject"])){
    $PatientName = $_POST['Patient_Name'];
    $DateTime = $_POST['Date_Time'];
    $BloodGroup = $_POST['BloodGroup'];
    $UnitsRequested = $_POST['Units'];

    // Update Status in the database
    $stmt = $conn->prepare("UPDATE Blood_Request SET Status = 'Rejected' WHERE (Patient_Name = ? AND RequestDate = ?)");
    $stmt->bind_param("ss", $PatientName, $DateTime);
    $stmt->execute();

    // Update Orders in the database
    $stmt = $conn->prepare("UPDATE Blood_Bank SET Orders = Orders - ? WHERE Blood_Type = ?");
    $stmt->bind_param("is", $UnitsRequested, $BloodGroup);
    $stmt->execute();

    // Close Database Connection
    $stmt->close();
    $conn->close();

    // Display Message
    header("Location: BloodRequests.php");
    $_SESSION["Message"] = "Blood Request Rejected.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Blood Requests</title>
    <link rel="stylesheet" href="AdminStyles/Header.css">
    <link rel="stylesheet" href="AdminStyles/AdminSidebar.css">
    <link rel="stylesheet" href="AdminStyles/BloodRequests.css">
</head>
<body style="margin: 0; padding-top: 40px; background-color: rgb(243, 245, 249);">
    <div class="menu">
        <div class="ProjectTitle"><h1>BLOOD DONATION MANAGEMENT SERVICE</h1></div> 
        <div class="Logout">
            <a href="../AdminLogin.php" style="text-decoration: none;"><h1>Logout</h1></a>
        </div>
    </div>
    <div class="Contents">
        <div class="Sidebar">
        <div id="AdminDashboard" class="SidebarBoxes Text">Home</div>
            <div id="Donor" class="SidebarBoxes Text">Donor</div>
            <div id="Patient" class="SidebarBoxes Text">Patient</div>
            <div id="Donations" class="SidebarBoxes Text">Donation Request</div>
            <div id="BloodRequests" class="SidebarBoxes Text">Blood Requests</div>
            <div id="DonationHistory" class="SidebarBoxes Text">Donation History</div>
            <div id="RequestHistory" class="SidebarBoxes Text">Request History</div>
            <div id="BloodStock" class="SidebarBoxes BloodStock Text">Blood Stock</div>
            <script>
                const AdminDashboardBtn = document.getElementById("AdminDashboard");
                const DonorBtn = document.getElementById("Donor");
                const PatientBtn = document.getElementById("Patient");
                const DonationsBtn = document.getElementById("Donations");
                const BloodRequestsBtn = document.getElementById("BloodRequests");
                const DonationHistoryBtn = document.getElementById("DonationHistory");
                const RequestHistoryBtn = document.getElementById("RequestHistory");
                const BloodStockBtn = document.getElementById("BloodStock");

                AdminDashboardBtn.addEventListener("click", (e) => GoToHome());
                DonorBtn.addEventListener("click", (e) => GoToDonorDetails());
                PatientBtn.addEventListener("click", (e) => GoToPatientDetails());
                DonationsBtn.addEventListener("click", (e) => GoToDonationDetails());
                BloodRequestsBtn.addEventListener("click", (e) => GoToBloodRequests());
                DonationHistoryBtn.addEventListener("click", (e) => GoToDonationHistory());
                RequestHistoryBtn.addEventListener("click", (e) => GoToRequestHistory());
                BloodStockBtn.addEventListener("click", (e) => GoToBloodStock());
            </script>
        </div>
        <div>
        <?php
            if (isset($_SESSION['Error']))
            {
            ?>
                <div class="Alert">
                <span class="Closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <?php echo $_SESSION["Error"];?>
                </div>
            <?php
                unset($_SESSION['Error']);
            }
            ?>
            <?php
            if (isset($_SESSION['Message']))
            {
            ?>
                <div class="Success">
                <span class="Closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <?php echo $_SESSION["Message"];?>
                </div>
            <?php
                unset($_SESSION['Message']);
            }
            ?>
        </div>
        <p class="BloodRequestsTitle">BLOOD REQUESTS</p>
        <table class="BloodRequestsTable Text">
            <tr style="background-color: rgb(23, 162, 184);">
                <th>Patient Name</th>
                <th>Age</th>
                <th>Blood Group</th>
                <th>Reason</th>
                <th>Units</th>
                <th>Date & Time</th>
                <th>Action</th>
            </tr>
            <?php
            while(($row = mysqli_fetch_array($result))){
            ?>
                <tr style="background-color: rgb(227, 226, 226);">
                    <td><?php echo $row['Patient_Name']?></td>
                    <td><?php echo $row['Age']?></td>
                    <td><?php echo $row['Blood_Group']?></td>
                    <td><?php echo $row['Reason']?></td>
                    <td><?php echo $row['Units']?></td>
                    <td><?php echo $row['RequestDate']?></td>
                    <td style="display: flex; width: 180px;">
                        <form action="BloodRequests.php" method="post">
                            <input type="submit" class="ActionButton Approve" name="Approve" value="APPROVE">
                            <input type="hidden" name="Patient_Name" value="<?php echo $row['Patient_Name'];?>">
                            <input type="hidden" name="Date_Time" value="<?php echo $row['RequestDate'];?>">
                            <input type="hidden" name="BloodGroup" value="<?php echo $row['Blood_Group'];?>">
                            <input type="hidden" name="Units" value="<?php echo $row['Units'];?>">
                        </form>
                        <form action="BloodRequests.php" method="post">
                            <input type="submit" class="ActionButton Reject" name="Reject" value="REJECT">
                            <input type="hidden" name="Patient_Name" value="<?php echo $row['Patient_Name'];?>">
                            <input type="hidden" name="Date_Time" value="<?php echo $row['RequestDate'];?>">
                            <input type="hidden" name="BloodGroup" value="<?php echo $row['Blood_Group'];?>">
                            <input type="hidden" name="Units" value="<?php echo $row['Units'];?>">
                        </form>
                    </td>
                </tr>
            <?php
            }
            ?>
                
            </tr>
        </table>
    </div>
</body>
</html>

<script>
function GoToHome(){
    window.location.assign("AdminDashboard.php");
}
function GoToDonorDetails(){
    window.location.assign("DonorDetails.php");
}
function GoToPatientDetails(){
    window.location.assign("PatientDetails.php");
}
function GoToDonationDetails(){
    window.location.assign("DonationRequest.php");
}
function GoToBloodRequests(){
    window.location.assign("BloodRequests.php");
}
function GoToDonationHistory(){
    window.location.assign("DonationHistory.php");
}
function GoToRequestHistory(){
    window.location.assign("RequestHistory.php");
}
function GoToBloodStock(){
    window.location.assign("BloodStock.php");
}
</script>
