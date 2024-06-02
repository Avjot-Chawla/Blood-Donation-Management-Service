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
    $stmt = "Select * from blood_request where Username='$Username'";
    $result = mysqli_query($conn,$stmt);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Request History</title>
    <link rel="stylesheet" href="PatientStyles/Header.css">
    <link rel="stylesheet" href="PatientStyles/PatientSidebar.css">
    <link rel="stylesheet" href="PatientStyles/RequestHistory.css">
</head>
<body style="margin: 0; padding-top: 40px; background-color: rgb(243, 245, 249);">
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
        <p class="RequestHistoryTitle">My Blood Requests</p>
        <table class="RequestTable Text">
            <tr class="TableHeader">
                <th>Patient Name</th>
                <th>Patient Age</th>
                <th>Reason</th>
                <th>Blood Group</th>
                <th>Units</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
            <?php
            while(($row = mysqli_fetch_array($result))){
            ?>
                <tr class="TableData">
                    <td><?php echo $row['Patient_Name']?></td>
                    <td><?php echo $row['Age']?></td>
                    <td><?php echo $row['Reason']?></td>
                    <td><?php echo $row['Blood_Group']?></td>
                    <td><?php echo $row['Units']?></td>
                    <td><?php echo $row['RequestDate']?></td>
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
    window.location.assign("PatientDashboard.php");
}
function GoToMakeRequest(){
    window.location.assign("MakeRequest.php");
}
function GoToRequestHistory(){
    window.location.assign("RequestHistory.php");
}
</script>

<?php
$conn->close();
?>