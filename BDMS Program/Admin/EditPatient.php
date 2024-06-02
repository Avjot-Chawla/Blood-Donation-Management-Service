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
    // Retrive the username of user which is to be edited
    $User_To_Edit = $_SESSION['Username_To_Edit'];

    // Retrive current details of Patient from database
    $stmt = "SELECT * FROM Patient WHERE Username = '$User_To_Edit'";
    $result = mysqli_query($conn,$stmt);
    $row = mysqli_fetch_array($result);

    if (isset($_POST["Confirm"])){
        //Store Values
        $FullName = $_POST["FullName"];
        $Username = $_POST["Username"];
        $Password = $_POST["Password"];
        $Gender = $_POST["Gender"];
        $DOB = $_POST["DOB"];
        $MobileNumber = $_POST["MobileNumber"];
        $BloodGroup = $_POST["BloodGroup"];
        $Disease = $_POST["Disease"];
        
        // Check if any field is empty
        if ((trim($FullName) == "") || (trim($Username) == "") || (trim($Password) == "") || 
        (trim($DOB) == "") || (trim($MobileNumber) == "") || (trim($Disease) == "")){
            header("Location: EditPatient.php");
            $_SESSION["Error"] = "All Fields are Mandatory.";
            exit;
        }
        else{
            // Check if username alredy exists
            $stmt = "SELECT Username FROM Patient WHERE Username='$Username'";
            $result = mysqli_query($conn,$stmt);
            $row = mysqli_fetch_array($result);

            if (($row['Username'] == $User_To_Edit) || (mysqli_num_rows($result) == 0)){
                // Update Patient table in the database
                $stmt = $conn->prepare("UPDATE Patient SET Username=?, Name=?, Gender=?, DOB=?, PhoneNumber=?, Blood_Group=?, Medical_History = ?, Password=? WHERE Username=?");
                $stmt->bind_param("ssssissss", $Username, $FullName, $Gender, $DOB, $MobileNumber, $BloodGroup, $Disease, $Password, $User_To_Edit);
                $stmt->execute();

                // Update Patient History table in the database
                $stmt = $conn->prepare("UPDATE Blood_Request SET Username=? WHERE Username=?");
                $stmt->bind_param("ss", $Username, $User_To_Edit);
                $stmt->execute();

                // Close Connection
                $stmt->close();
                $conn->close();

                // Display Message
                header("Location: PatientDetails.php");
                $_SESSION["Message"] = "Patient details updated successfully.";
                exit;
            }
            else{
                // Display error message
                header("Location: EditPatient.php");
                $_SESSION["Error"] = "Username Already Exists.";
                exit;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Patient</title>
    <link rel="stylesheet" href="AdminStyles/Header.css">
    <link rel="stylesheet" href="AdminStyles/AdminSidebar.css">
    <link rel="stylesheet" href="AdminStyles/EditPatient.css">
</head>
<body style="margin: 0; padding-top: 40px; background-color: rgb(0, 120, 120);">
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
        </div>
        <div class="EditPatientPage">
            <div style="width: 100%; border-radius: 10px;">
            <div class="EditPatientTitle Text">EDIT PATIENT</div>
                <form action="EditPatient.php" method="post">
                <div style="display: flex; margin: 10px 50px 0 50px;" class="Text">
                    <div class="FormText">
                        <p style="margin-bottom: 40px;">FULL NAME</p>
                        <p style="margin-bottom: 40px;">USERNAME</p>
                        <p style="margin-bottom: 40px;">PASSWORD</p>
                        <p style="margin-bottom: 40px;">GENDER</p>
                        <p style="margin-bottom: 40px;">DOB</p>
                        <p style="margin-bottom: 40px;">MOBILE NUMBER</p>
                        <p style="margin-bottom: 40px;">BLOOD GROUP</p>
                        <p style="margin-bottom: 12px;">Disease (if any)</p>
                    </div>
                    <div style="flex: 1; display: block;">
                        <input class="TextBoxes" value="<?php echo $row['Name'];?>" placeholder="Full Name" type="text" name="FullName">
                        <input class="TextBoxes" value="<?php echo $row['Username'];?>" placeholder="Username" type="text" name="Username">
                        <input class="TextBoxes" value="<?php echo $row['Password'];?>" placeholder="Password" type="password" name="Password">
                        <select class="TextBoxes DropDownMenu" name="Gender">
                            <option <?php if($row['Gender'] == 'Male') echo 'selected'; ?>>Male</option>
                            <option <?php if($row['Gender'] == 'Female') echo 'selected'; ?>>Female</option>
                            <option <?php if($row['Gender'] == 'Others') echo 'selected'; ?>>Others</option>
                        </select>
                        <input class="TextBoxes" value=<?php echo $row['DOB'];?> placeholder="YYYY-MM-DD" type="text" name="DOB">
                        <input class="TextBoxes" value=<?php echo $row['PhoneNumber'];?> placeholder="Mobile Number" type="text" name="MobileNumber">
                        <select class="TextBoxes DropDownMenu" name="BloodGroup">
                            <option <?php if($row['Blood_Group'] == 'A+') echo 'selected';?>>A+</option>
                            <option <?php if($row['Blood_Group'] == 'A-') echo 'selected';?>>A-</option>
                            <option <?php if($row['Blood_Group'] == 'B+') echo 'selected';?>>B+</option>
                            <option <?php if($row['Blood_Group'] == 'B-') echo 'selected';?>>B-</option>
                            <option <?php if($row['Blood_Group'] == 'O+') echo 'selected';?>>O+</option>
                            <option <?php if($row['Blood_Group'] == 'O-') echo 'selected';?>>O-</option>
                            <option <?php if($row['Blood_Group'] == 'AB+') echo 'selected';?>>AB+</option>
                            <option <?php if($row['Blood_Group'] == 'AB-') echo 'selected';?>>AB-</option>
                        </select>
                        <input class="TextBoxes" style="margin-bottom: 12px;" value="<?php echo $row['Medical_History'];?>" type="text" name="Disease" placeholder="Disease">
                    </div>
                </div>
                <div style="display: flex; justify-content: center;">
                    <button class="ConfirmButton Text" type="submit" name="Confirm">Confirm</button>
                </div>
                </form>
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

