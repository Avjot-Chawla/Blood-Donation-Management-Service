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
    $stmt = "SELECT * FROM Donation_History WHERE Username='$Username'";
    $result = mysqli_query($conn,$stmt);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Donation History</title>
    <link rel="stylesheet" href="DonorStyles/Header.css">
    <link rel="stylesheet" href="DonorStyles/DonorSidebar.css">
    <link rel="stylesheet" href="DonorStyles/DonationHistory.css">
</head>
<body style="margin: 0; padding-top: 40px; background-color: rgb(243, 245, 249);">
    <div class="menu">
        <div class="ProjectTitle"><h1>BLOOD DONATION MANAGEMENT SERVICE</h1></div> 
        <div class="Logout">
            <a href="../DonorLogin.php" style="text-decoration: none;"><h1>Logout</h1></a>
        </div>
    </div>
    <div class="Contents">
        <div class="Sidebar">
            <div id="DonorDashboard" class="SidebarBoxes Text">Home</div>
            <div id="DonateBlood" class="SidebarBoxes Text">Donate Blood</div>
            <div id="DonationHistory" class="SidebarBoxes DonationHistoy Text">Donation History</div>
            <script>
                const DonorDashboardBtn = document.getElementById("DonorDashboard");
                const DonateBloodBtn = document.getElementById("DonateBlood");
                const DonationHistoryBtn = document.getElementById("DonationHistory");

                DonorDashboardBtn.addEventListener("click", (e) => GoToHome());
                DonateBloodBtn.addEventListener("click", (e) => GoToDonateBlood());
                DonationHistoryBtn.addEventListener("click", (e) => GoToDonationHistory());
            </script>
        </div>
        <p class="DonorHistoryTitle">My Blood Donations</p>
        <table class="DonorTable Text">
            <tr class="TableHeader">
                <th>Donor Name</th>
                <th>Donor Age</th>
                <th>Disease (if any)</th>
                <th>Blood Group</th>
                <th>Units</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
            <?php
            while(($row = mysqli_fetch_array($result))){
            ?>
                <tr class="TableData">
                    <td><?php echo $row['Donor_Name']?></td>
                    <td><?php echo $row['Age']?></td>
                    <td><?php echo $row['Disease']?></td>
                    <td><?php echo $row['Blood_Group']?></td>
                    <td><?php echo $row['Units']?></td>
                    <td><?php echo $row['DonationDate']?></td>
                    <td><div class="Status"><?php echo $row['Status'];?></div></td>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>
</body>
</html>

<script>
function GoToHome(){
    window.location.assign("DonorDashboard.php");
}
function GoToDonateBlood(){
    window.location.assign("DonateBlood.php");
}
function GoToDonationHistory(){
    window.location.assign("DonationHistory.php");
}
</script>