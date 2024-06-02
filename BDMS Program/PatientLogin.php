<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient Login</title>
    <link rel="stylesheet" href="styles/HomeHeader.css">
    <link rel="stylesheet" href="styles/PatientLogin.css">
</head>
<body style="margin: 0; padding-top: 40px; background-color: rgb(237, 66, 47);">
    <div class="menu">
        <div class="ProjectTitle"><h1>BLOOD DONATION MANAGEMENT SERVICE</h1></div> 
        <div class="Home">
            <a href="Home.html" style="text-decoration: none;"><h1>Home</h1></a>
        </div>
        <div class="Patient">
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
    <div class="PatientLoginScreen">
        <div class="PatientLoginBox">
            <div class="PatientLoginText">PATIENT LOGIN</div>
            <form action="PatientLogin.php" method="post">
                <div style="display: flex;">
                    <p class="UsernameText">USERNAME</p>
                    <input class="UsernameTextBox" placeholder="Username" name="Username">
                </div>
                <div style="display: flex;">
                    <p class="PasswordText">PASSWORD</p>
                    <input type="password" class="PasswordTextBox" placeholder="Password" name="Password">
                </div>
                <button class="LoginButton" type="submit" name="Login">Login</button>
            </form>
            <p class="PatientSignupText">Don't have an account? <a href="PatientSignup.php">Click here to register</a></p>
        </div>
    </div>
</body>
</html>

<?php
if (isset($_POST["Login"])){
    //Store Values
    $Username = $_POST["Username"];
    $Password = $_POST["Password"];
    
    // Check if any field is empty
    if ((trim($Username) == "") || (trim($Password) == "")){
        header("Location: PatientLogin.php");
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
            // Check if credentials are correct
            $stmt = "SELECT Username,Password FROM Patient WHERE Username='$Username' AND Password='$Password'";
            $result = mysqli_query($conn,$stmt);

            if (mysqli_num_rows($result) == 0){
                // Display error message
                header("Location: PatientLogin.php");
                $_SESSION["Error"] = "Username or Password Incorrect.";
            }
            else{
                session_unset();
                session_destroy();
                session_start();
                $_SESSION["Username"] = $Username;
                header("Location: Patient/PatientDashboard.php");
            }
        }
    }
}
?>
