<?php
session_start();
global $Username;
if (isset($_SESSION["Username"])){
    $Username = $_SESSION["Username"];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Make Request</title>
    <link rel="stylesheet" href="PatientStyles/Header.css">
    <link rel="stylesheet" href="PatientStyles/PatientSidebar.css">
    <link rel="stylesheet" href="PatientStyles/MakeRequest.css">
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
        <div class="RequestPage">
            <div style="width: 100%; border-radius: 10px;">
            <div class="RequestBoxTitle Text">MAKE BLOOD REQUEST</div>
                <form action="MakeRequest.php" method="post">
                <div style="display: flex; margin: 25px 50px 0 50px;" class="Text">
                    <div class="FormText">
                        <p style="margin-bottom: 50px;">PATIENT NAME</p>
                        <p style="margin-bottom: 50px;">PATIENT AGE</p>
                        <p style="margin-bottom: 50px;">REASON</p>
                        <p style="margin-bottom: 50px;">BLOOD GROUP</p>
                        <p style="margin-bottom: 50px;">UNITS</p>
                    </div>
                    <div style="flex: 1; display: block;">
                        <input class="TextBoxes" placeholder="Patient Name" type="text" name="PatientName">
                        <input class="TextBoxes" placeholder="Patient Age" type="text" name="Age">
                        <input class="TextBoxes" placeholder="Reason" type="text" name="Reason">
                        <select class="BloodGroupMenu" name="BloodGroup">
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
                        <input class="TextBoxes" placeholder="Units" type="text" name="Units">
                    </div>
                </div>
                <div style="display: flex; justify-content: center;">
                    <button class="RequestButton Text" type="submit" name="Request">Request</button>
                </div>
                </form>
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

<?php
if(isset($_POST["Request"])){
    $PatientName = $_POST["PatientName"];
    $Age = $_POST["Age"];
    $Reason = $_POST["Reason"];
    $BloodGroup = $_POST["BloodGroup"];
    $Units = $_POST["Units"];

    // Check if any field is empty
    if ((trim($PatientName) == "") || (trim($Age) == "") || (trim($Reason) == "") || ($BloodGroup == "Choose Blood Group") || (trim($Units) == "")){
        header("Location: MakeRequest.php");
        $_SESSION["Error"] = "All Fields are Mandatory.";
    }
    else{
        // Connect Database
        $conn = new mysqli('localhost', 'root', 'root', 'bdms_db');

        // If connection not successfull
        if ($conn->connect_error){
            die("Connection Failed: ".$conn->connect_error);
        }
        else{
            // Insert data into database
            $stmt = $conn->prepare("INSERT INTO Blood_Request(Username, Patient_Name, Age, Reason, Blood_Group, Units) VALUES(?,?,?,?,?,?)");
            $stmt->bind_param("ssissi", $Username, $PatientName, $Age, $Reason, $BloodGroup, $Units);
            $stmt->execute();

            $stmt = $conn->prepare("UPDATE Blood_Bank SET Orders = Orders + ? WHERE Blood_Type = ?");
            $stmt->bind_param("is", $Units, $BloodGroup);
            $stmt->execute();

            $stmt->close();
            $conn->close();
            header("Location: MakeRequest.php");
            $_SESSION["Message"] = "Request Made Successfully.";
        }
        $conn->close();
    }
}
?>