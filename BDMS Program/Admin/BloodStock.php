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
    // Fetch units of blood from database
    $stmt = "SELECT * FROM Blood_Bank";
    $result = mysqli_query($conn,$stmt);

    while(($row = mysqli_fetch_array($result))){
        if ($row['Blood_Type'] == "A+"){
            $A_Positive = $row['Units'];
        }
        else if ($row['Blood_Type'] == "A-"){
            $A_Negative = $row['Units'];
        }
        else if ($row['Blood_Type'] == "B+"){
            $B_Positive = $row['Units'];
        }
        else if ($row['Blood_Type'] == "B-"){
            $B_Negative = $row['Units'];
        }
        else if ($row['Blood_Type'] == "O+"){
            $O_Positive = $row['Units'];
        }
        else if ($row['Blood_Type'] == "O-"){
            $O_Negative = $row['Units'];
        }
        else if ($row['Blood_Type'] == "AB+"){
            $AB_Positive = $row['Units'];
        }
        else if ($row['Blood_Type'] == "AB-"){
            $AB_Negative = $row['Units'];
        }
    }
}

// Check if Add button is clicked
if (isset($_POST["Add"])){
    // Store Values
    $BloodGroup =  $_POST['BloodGroup'];
    $Units_to_add = $_POST['Units'];

    // Check if any field is empty
    if (($BloodGroup == "Choose Blood Group") || (trim($Units_to_add) == "")){
        header("Location: BloodStock.php");
        $_SESSION["Error"] = "All Fields are Mandatory.";
        exit;
    }
    else if (!is_numeric($Units_to_add)){
        header("Location: BloodStock.php");
        $_SESSION["Error"] = "Please enter a numeric value.";
        exit;
    }
    else{
        // Connect Database
        $conn = new mysqli('localhost', 'root', 'root', 'bdms_db');

        // If connection not successfull
        if ($conn->connect_error){
            die("Connection Failed: ".$conn->connect_error);
        }
        else{
            // Update data in the database
            $stmt = $conn->prepare("UPDATE Blood_Bank SET Units = Units + ? WHERE Blood_Type = ?");
            $stmt->bind_param("is", $Units_to_add, $BloodGroup);
            $stmt->execute();

            // Store Blood Donation details in the database
            $stmt = $conn->prepare("INSERT INTO Blood(Blood_Type, Units) VALUES (?,?)");
            $stmt->bind_param("si", $BloodGroup, $Units_to_add);
            $stmt->execute();

            // Close Database Connection
            $stmt->close();
            $conn->close();

            // Display Message
            header("Location: BloodStock.php");
            $_SESSION["Message"] = "Data Successfully Updated.";
            exit;
        }
    }
}

// Check if Remove button is clicked
if (isset($_POST["Remove"])){
    // Store Values
    $BloodGroup =  $_POST['BloodGroup'];
    $Units_to_remove = $_POST['Units'];

    // Check if any field is empty
    if (($BloodGroup == "Choose Blood Group") || (trim($Units_to_remove) == "")){
        header("Location: BloodStock.php");
        $_SESSION["Error"] = "All Fields are Mandatory.";
        exit;
    }
    else if (!is_numeric($Units_to_remove)){
        header("Location: BloodStock.php");
        $_SESSION["Error"] = "Please enter a numeric value.";
        exit;
    }
    else{
        // Connect Database
        $conn = new mysqli('localhost', 'root', 'root', 'bdms_db');

        // If connection not successfull
        if ($conn->connect_error){
            die("Connection Failed: ".$conn->connect_error);
        }
        else{
            // Update data in the database
            $stmt = $conn->prepare("UPDATE Blood_Bank SET Units = Units - ? WHERE Blood_Type = ?");
            $stmt->bind_param("is", $Units_to_remove, $BloodGroup);
            $stmt->execute();
            $stmt->close();
            $conn->close();
            header("Location: BloodStock.php");
            $_SESSION["Message"] = "Data Successfully Updated.";
            exit;
        }
    }
}
?>
    
<!DOCTYPE html>
<html>
<head>
    <title>Blood Stock</title>
    <link rel="stylesheet" href="AdminStyles/Header.css">
    <link rel="stylesheet" href="AdminStyles/AdminSidebar.css">
    <link rel="stylesheet" href="AdminStyles/BloodStock.css">
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
        <div style="margin-left: 200px; width: 100%;">
            <div>
                <div style="display: flex;">
                    <div class="BloodStockInfoBox Text">
                        A+<br><br>
                        <?php echo $A_Positive; ?>
                    </div>
                    <div class="BloodStockInfoBox Text">
                        B+<br><br>
                        <?php echo $B_Positive; ?>
                    </div>
                    <div class="BloodStockInfoBox Text">
                        O+<br><br>
                        <?php echo $O_Positive; ?>
                    </div>
                    <div class="BloodStockInfoBox Text">
                        AB+<br><br>
                        <?php echo $AB_Positive; ?>
                    </div>
                </div>
                <div style="display: flex;">
                    <div class="BloodStockInfoBox Text">
                        A-<br><br>
                        <?php echo $A_Negative; ?>
                    </div>
                    <div class="BloodStockInfoBox Text">
                        B-<br><br>
                        <?php echo $B_Negative; ?>
                    </div>
                    <div class="BloodStockInfoBox Text">
                        O-<br><br>
                        <?php echo $O_Negative; ?>
                    </div>
                    <div class="BloodStockInfoBox Text">
                        AB-<br><br>
                        <?php echo $AB_Negative; ?>
                    </div>
                </div>
            </div>
            <hr class="Line">
            <div class="InfoBox Text">
                <div style="margin: 30px 0 20px 0; width: 100%; text-align: center; font-size: 20px;">UPDATE BLOOD UNITS</div>
                <div style="display: flex; align-items: center; width: fit-content; margin-left: 210px;">
                    <form action="BloodStock.php" method="post">
                    <select name="BloodGroup" class="BloodGroupDropDown">
                        <option>Choose Blood Group</option>
                        <option>O+</option>
                        <option>O-</option>
                        <option>A+</option>
                        <option>A-</option>
                        <option>B+</option>
                        <option>B-</option>
                        <option>AB+</option>
                        <option>AB-</option>
                    </select>
                    <input class="UpdateUnitsEntryBox" name="Units" placeholder="Units">
                    <input class="UpdateUnitsButton" type="submit" name="Add" value="Add">
                    <input class="UpdateUnitsButton" type="submit" name="Remove" value="Remove">
                    </form>
                </div>
            </div>
        </div>
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
