<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['client_logged_in'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost"; // Your database server
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "members_db"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $phone_number = $_POST['phone_number'];
    $organization = $_POST['organization'];
    $area_of_specialization = $_POST['area_of_specialization'];

    $stmt = $conn->prepare("INSERT INTO members (full_name, phone_number, organization, area_of_specialization) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $full_name, $phone_number, $organization, $area_of_specialization);

    if ($stmt->execute()) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Member</title>
</head>
<body>
    <h2>Add New Member</h2>
    <form method="POST">
        <input type="text" name="full_name" placeholder="Full Name" required>
        <input type="text" name="phone_number" placeholder="Phone Number" required>
        <input type="text" name="organization" placeholder="Organization" required>
        <input type="text" name="area_of_specialization" placeholder="Area of Specialization" required>
        <button type="submit">Add Member</button>
    </form>
</body>
</html>