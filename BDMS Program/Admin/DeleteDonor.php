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
    // Retrive the username of user which is to be deleted
    $User_To_Delete = $_POST['Username_To_Delete'];

    // Delete user from the database
    $stmt = $conn->prepare("DELETE FROM Donor WHERE Username = ?");
    $stmt->bind_param("s", $User_To_Delete);
    $stmt->execute();

    // Delete pending donation requests of user from the database
    $stmt = $conn->prepare("DELETE FROM Donation_History WHERE Username = ? AND Status = 'Pending'");
    $stmt->bind_param("s", $User_To_Delete);
    $stmt->execute();

    // Close Database Connection
    $stmt->close();
    $conn->close();

    // Display Message
    header("Location: DonorDetails.php");
    $_SESSION["Message"] = "Donor Deleted Successfully.";
    exit;
}