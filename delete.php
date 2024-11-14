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

$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM members WHERE id=?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: admin.php");
    exit();
} else {
    echo "Error deleting record: " . $stmt->error;
}
?>