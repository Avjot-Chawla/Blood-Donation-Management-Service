<?php
session_start();

global $Donations_Made;
global $Pending_Donations;
global $Approved_Donations;
global $Rejected_Donations;

$Donations_Made = 0;
$Pending_Donations = 0;
$Approved_Donations = 0;
$Rejected_Donations = 0;

if (isset($_SESSION["Username"])){
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

        $Donations_Made = mysqli_num_rows($result);
        while(($row = mysqli_fetch_array($result))){

            if ($row['Status'] == "Pending"){
                $Pending_Donations += 1;
            }
            if ($row['Status'] == "Approved"){
                $Approved_Donations += 1;
            }
            if ($row['Status'] == "Rejected"){
                $Rejected_Donations += 1;
            }
        }
    }
    $conn->close();
}
else{
    header("Location: ../DonorLogin.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Donor</title>
    <link rel="stylesheet" href="DonorStyles/Header.css">
    <link rel="stylesheet" href="DonorStyles/DonorSidebar.css">
    <link rel="stylesheet" href="DonorStyles/DonorDashboard.css">
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
        <div class="InfoBox">
            <div class="DonorInfoBox Text">
                Donations Made<br><br>
                <div><?php echo $Donations_Made;?></div>
            </div>
            <div class="DonorInfoBox Text">
                Pending Donations<br><br>
                <div><?php echo $Pending_Donations;?></div>
            </div>
            <div class="DonorInfoBox Text">
                Approved Donations<br><br>
                <div><?php echo $Approved_Donations;?></div>
            </div>
            <div class="DonorInfoBox Rejected Text">
                Rejected Donations<br><br>
                <div><?php echo $Rejected_Donations;?></div>
            </div>
        </div>
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
