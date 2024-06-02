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
    $stmt = "SELECT * FROM Blood_Request WHERE (Status = 'Approved' OR Status = 'Rejected')";
    $result = mysqli_query($conn,$stmt);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Blood Request History</title>
    <link rel="stylesheet" href="AdminStyles/Header.css">
    <link rel="stylesheet" href="AdminStyles/AdminSidebar.css">
    <link rel="stylesheet" href="AdminStyles/RequestHistory.css">
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
        <p class="RequestHistoryTitle">BLOOD REQUESTS HISTORY</p>
        <table class="RequestHistoryTable Text">
            <tr style="background-color: rgb(23, 162, 184);">
            <th>Patient Name</th>
                <th>Age</th>
                <th>Blood Group</th>
                <th>Reason</th>
                <th>Units</th>
                <th>Date & Time</th>
                <th>Status</th>
                <th>Stock Update</th>
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
                    <td><?php echo $row['Status']?></td>
                    <td style="display: flex; width: 245px;">
                        <div class="StatusUpdate">
                            <?php
                            if ($row['Status'] == "Approved")
                                echo $row['Units'];
                            else
                                echo 0;
                            ?> 
                            Units deducted from Stock
                        </div>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>
</body>
</html>

<?php
if (isset($_GET["Approve"])){
    header("Location: AdminDashboard.php");
}
?>

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
