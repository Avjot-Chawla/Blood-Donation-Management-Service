<?php
session_start();

global $Requests_Made;
global $Pending_Requests;
global $Approved_Requests;
global $Rejected_Requests;
$Requests_Made = 0;
$Pending_Requests = 0;
$Approved_Requests = 0;
$Rejected_Requests = 0;

if (isset($_SESSION["Username"])){
    $Username = $_SESSION["Username"];

    // Connect Database
    $conn = new mysqli('localhost', 'root', 'root', 'bdms_db');

    // If connection not successfull
    if ($conn->connect_error){
        die("Connection Failed: ".$conn->connect_error);
    }
    else{
        $stmt = "Select * from blood_request where Username='$Username'";
        $result = mysqli_query($conn,$stmt);

        $Requests_Made = mysqli_num_rows($result);
        while(($row = mysqli_fetch_array($result))){

            if ($row['Status'] == "Pending"){
                $Pending_Requests += 1;
            }
            if ($row['Status'] == "Approved"){
                $Approved_Requests += 1;
            }
            if ($row['Status'] == "Rejected"){
                $Rejected_Requests += 1;
            }
        }
    }
    $conn->close();
}
else{
    // header("Location: ../PatientLogin.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient</title>
    <link rel="stylesheet" href="PatientStyles/Header.css">
    <link rel="stylesheet" href="PatientStyles/PatientSidebar.css">
    <link rel="stylesheet" href="PatientStyles/PatientDashboard.css">
</head>
<body style="margin: 0; padding-top: 40px;">
    <div class="menu">
        <div class="ProjectTitle"><h1>BLOOD DONATION MANAGEMENT SERVICE</h1></div> 
        <div class="Logout">
            <a href="../PatientLogin.php" style="text-decoration: none;"><h1>Logout</h1></a>
        </div>
    </div>
    <div class="Contents">
        <div class="Sidebar">
            <div id="PatientDashboard" class="SidebarBoxes Text">Home</div>
            <div id="MakeRequest" class="SidebarBoxes Text">Make Request</div>
            <div id="RequestHistory" class="SidebarBoxes RequestHistoy Text">Request History</div>
            <script>
                const PatientDashboardBtn = document.getElementById("PatientDashboard");
                const MakeRequestBtn = document.getElementById("MakeRequest");
                const RequestHistoryBtn = document.getElementById("RequestHistory");

                PatientDashboardBtn.addEventListener("click", (e) => GoToHome());
                MakeRequestBtn.addEventListener("click", (e) => GoToMakeRequest());
                RequestHistoryBtn.addEventListener("click", (e) => GoToRequestHistory());
            </script>
        </div>
        <div class="InfoBox">
            <div class="PatientInfoBox Text">
                Requests Made<br><br>
                <div><?php echo $Requests_Made;?></div>
            </div>
            <div class="PatientInfoBox Text">
                Pending Requests<br><br>
                <div><?php echo $Pending_Requests;?></div>
            </div>
            <div class="PatientInfoBox Text">
                Approved Requests<br><br>
                <div><?php echo $Approved_Requests;?></div>
            </div>
            <div class="PatientInfoBox Rejected Text">
                Rejected Requests<br><br>
                <div><?php echo $Rejected_Requests;?></div>
            </div>
        </div>
    </div>
</body>
</html>

<script>
function GoToHome(){
    window.location.assign("PatientDashboard.php");
}
function GoToMakeRequest(){
    window.location.assign("MakeRequest.php");
}
function GoToRequestHistory(){
    window.location.assign("RequestHistory.php");
}
</script>
