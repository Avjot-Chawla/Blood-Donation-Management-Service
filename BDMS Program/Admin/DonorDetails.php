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
    $stmt = "SELECT * FROM Donor";
    $result = mysqli_query($conn,$stmt);

    // Edit button presssed
    if (isset($_POST['Edit'])){
        $_SESSION['Username_To_Edit'] = $_POST['Username_To_Edit'];
        header("Location: EditDonor.php");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Donor Details</title>
    <link rel="stylesheet" href="AdminStyles/Header.css">
    <link rel="stylesheet" href="AdminStyles/AdminSidebar.css">
    <link rel="stylesheet" href="AdminStyles/DonorDetails.css">
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
        <p class="DonorDetailsTitle">DONOR DETAILS</p>
        <table class="DonorDetailsTable Text">
            <tr style="background-color: rgb(23, 162, 184);">
                <th>Full Name</th>
                <th>Username</th>
                <th>Gender</th>
                <th>DOB</th>
                <th>Blood Group</th>
                <th>Mobile Number</th>
                <th>Action</th>
            </tr>
            <?php
            while(($row = mysqli_fetch_array($result))){
            ?>
                <tr style="background-color: rgb(227, 226, 226);">
                    <td><?php echo $row['Name']?></td>
                    <td><?php echo $row['Username']?></td>
                    <td><?php echo $row['Gender']?></td>
                    <td><?php echo $row['DOB']?></td>
                    <td><?php echo $row['Blood_Group']?></td>
                    <td><?php echo $row['PhoneNumber']?></td>
                    <td style="display: flex; width: 180px;">
                        <form method="POST" action="DonorDetails.php">
                            <input type="submit" name="Edit" class="ActionButton Edit" value="EDIT">
                            <input type="hidden" name="Username_To_Edit" value="<?php echo $row['Username'];?>">
                        </form>
                        <form method="POST" action="DeleteDonor.php" onsubmit="DeleteDonor(this);">
                            <input type="submit" name="Delete" class="ActionButton Delete" value="DELETE">
                            <input type="hidden" name="Username_To_Delete" value="<?php echo $row['Username'];?>">
                        </form>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>
</body>
</html>

<script src="sweetalert.min.js"></script>

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

function DeleteDonor(form) {
    event.preventDefault();
    swal({
        title: "Are you sure?",
        text: "This donor details will be deleted permanently",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then(function (isOkay) {
        if (isOkay) {
            form.submit();
        }
    });
}
</script>

