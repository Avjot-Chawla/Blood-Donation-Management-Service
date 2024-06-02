<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Donor Signup</title>
    <link rel="stylesheet" href="styles/HomeHeader.css">
    <link rel="stylesheet" href="styles/DonorSignup.css">
</head>
<body style="margin: 0; padding-top: 40px; background-color: rgb(237, 66, 47);">
    <div class="menu">
        <div class="ProjectTitle"><h1>BLOOD DONATION MANAGEMENT SERVICE</h1></div> 
        <div class="Home">
            <a href="Home.html" style="text-decoration: none;"><h1>Home</h1></a>
        </div>
        <div class="Donor">
            <a href="PatientLogin.php" style="text-decoration: none;"><h1>Patient</h1></a>
        </div>
        <div class="Donor">
            <a href="DonorLogin.php" style="text-decoration: none;"><h1>Donor</h1></a>
        </div>
        <div class="Admin">
            <a href="AdminLogin.php" style="text-decoration: none;"><h1>Admin</h1></a>
        </div>
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
    <div class="DonorSignupScreen">
        <div class="DonorSignupBox">
            <div class="DonorSignupText">DONOR SIGNUP</div>
            <form action="DonorSignup.php" method="post">
                <div class="Content">
                    <div class="Text" style="margin: 10px 10px 10px 10px;">
                        <p style="margin-bottom: 50px;">FULL NAME</p>
                        <p style="margin-bottom: 50px;">USERNAME</p>
                        <p style="margin-bottom: 50px;">PASSWORD</p>
                        <p style="margin-bottom: 50px;">GENDER</p>
                        <p style="margin-bottom: 50px;">DATE OF BIRTH</p>
                        <p style="margin-bottom: 50px;">BLOOD GROUP</p>
                        <p style="margin-bottom: 50px;">DISEASE</p>
                        <p style="margin-bottom: 50px;">MOBILE NUMBER</p>
                    </div>
                    <div class="TextBox">
                        <input class="TextBoxes" placeholder="Full Name" type="text" name="FullName">
                        <input class="TextBoxes" placeholder="Username" type="text" name="Username">
                        <input class="TextBoxes" placeholder="Password" type="password" name="Password">
                        <select class="DropDownMenu" name="Gender">
                            <option>Choose Gender</option>
                            <option>Male</option>
                            <option>Female</option>
                            <option>Others</option>
                        </select>
                        <input class="TextBoxes" placeholder="YYYY-MM-DD" type="text" name="DOB">
                        <select class="DropDownMenu" name="BloodGroup">
                            <option>Choose Blood Group</option>
                            <option>A+</option>
                            <option>A-</option>
                            <option>B+</option>
                            <option>B-</option>
                            <option>O+</option>
                            <option>O-</option>
                            <option>AB+</option>
                            <option>AB-</option>
                        </select>
                        <input class="TextBoxes" placeholder="Disease" type="text" name="Disease">
                        <input class="TextBoxes" placeholder="Mobile Number" type="text" name="MobileNumber">
                    </div>
                </div>
                <button class="SignupButton" name="Signup" type="submit">Signup</button>
            </form>
            <p class="DonorLoginText">Already have an account? <a href="DonorLogin.php">Click here to Login</a></p>
        </div>
    </div>
</body>
</html>

<?php
if (isset($_POST["Signup"])){
    //Store Values
    $FullName = $_POST["FullName"];
    $Username = $_POST["Username"];
    $Password = $_POST["Password"];
    $Gender = $_POST["Gender"];
    $DOB = $_POST["DOB"];
    $BloodGroup = $_POST["BloodGroup"];
    $Disease = $_POST["Disease"];
    $MobileNumber = $_POST["MobileNumber"];
    
    // Check if any field is empty
    if ((trim($FullName) == "") || (trim($Username) == "") || (trim($Password) == "") || ($Gender == "Choose Gender") || (trim($DOB) == "") || 
        ($BloodGroup == "Choose Blood Group") || (trim($Disease) == "") || (trim($MobileNumber) == "")){
        header("Location: DonorSignup.php");
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
            // Check if username alredy exists
            $stmt = "SELECT Username FROM donor WHERE Username='$Username'";
            $result = mysqli_query($conn,$stmt);
            if (mysqli_num_rows($result) == 0){
                // Insert data into database
                $stmt = $conn->prepare("INSERT INTO donor(Username, Name, gender, DOB, PhoneNumber, Blood_Group, Medical_History, Password) VALUES(?,?,?,?,?,?,?,?)");
                $stmt->bind_param("ssssisss", $Username, $FullName, $Gender, $DOB, $MobileNumber, $BloodGroup, $Disease, $Password);
                $stmt->execute();
                $stmt->close();
                $conn->close();
                header("Location: DonorLogin.php");
                $_SESSION["Message"] = "Successfully Registered.";
            }
            // Display error message
            else{
                header("Location: DonorSignup.php");
                $_SESSION["Error"] = "Username Already Exists.";
            }
        }
    }
}
?>