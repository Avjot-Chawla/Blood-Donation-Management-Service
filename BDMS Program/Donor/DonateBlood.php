<?php
session_start();
global $Username;
if (isset($_SESSION["Username"])){
    $Username = $_SESSION["Username"];
}
if(isset($_POST["Donate"])){
    $DonorName = $_POST["DonorName"];
    $Age = $_POST["Age"];
    $Disease = $_POST["Disease"];
    $BloodGroup = $_POST["BloodGroup"];
    $Units = $_POST["Units"];

    // Check if any field is empty
    if ((trim($DonorName) == "") || (trim($Age) == "") || (trim($Disease) == "") || ($BloodGroup == "Choose Blood Group") || (trim($Units) == "")){
        header("Location: DonateBlood.php");
        $_SESSION["Error"] = "All Fields are Mandatory.";
        exit; // Exit after redirection
    }
    else{
        // Connect Database
        $conn = new mysqli('localhost', 'root', 'root', 'bdms_db');

        // If connection not successful
        if ($conn->connect_error){
            die("Connection Failed: ".$conn->connect_error);
        }
        else{
            // Insert data into database
            $stmt = $conn->prepare("INSERT INTO Donation_History(Username, Donor_Name, Age, Disease, Blood_Group, Units) VALUES (?,?,?,?,?,?)");
            $stmt->bind_param("ssissi", $Username, $DonorName, $Age, $Disease, $BloodGroup, $Units);
            $stmt->execute();
            $stmt->close();
            $conn->close();
            header("Location: DonateBlood.php");
            $_SESSION["Message"] = "Donation Request sent Successfully.";
            exit; // Exit after redirection
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Donate Blood</title>
    <link rel="stylesheet" href="DonorStyles/Header.css">
    <link rel="stylesheet" href="DonorStyles/DonorSidebar.css">
    <link rel="stylesheet" href="DonorStyles/DonateBlood.css">
</head>
<body style="margin: 0; padding-top: 40px;">
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
        <div class="DonatePage">
            <div style="width: 100%; border-radius: 10px;">
            <div class="DonateBoxTitle Text">DONATE BLOOD</div>
                <form action="DonateBlood.php" method="post">
                <div style="display: flex; margin: 25px 50px 0 50px;" class="Text">
                    <div class="FormText">
                        <p style="margin-bottom: 50px;">DONOR NAME</p>
                        <p style="margin-bottom: 50px;">DONOR AGE</p>
                        <p style="margin-bottom: 50px;">DISEASES (if any)</p>
                        <p style="margin-bottom: 50px;">BLOOD GROUP</p>
                        <p style="margin-bottom: 50px;">UNITS</p>
                    </div>
                    <div style="flex: 1; display: block;">
                        <input class="TextBoxes" placeholder="Donor Name" type="text" name="DonorName">
                        <input class="TextBoxes" placeholder="Donor Age" type="text" name="Age">
                        <input class="TextBoxes" value="NIL" placeholder="Diseases" type="text" name="Disease">
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
                    <button class="DonateButton Text" type="submit" name="Donate">Donate</button>
                </div>
                </form>
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
