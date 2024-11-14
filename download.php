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

// Fetch member data
$result = $conn->query("SELECT * FROM members");

// Set headers for the CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="members_list.csv"');

// Create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// Output the column headings
fputcsv($output, ['ID', 'Full Name', 'Phone Number', 'Organization', 'Area of Specialization']);

// Output the data rows
while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

// Close the file pointer
fclose($output);
exit();
?>