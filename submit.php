<?php
// Database connection details
$servername = "localhost"; // Usually "localhost"
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "members_db"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize inputs
    $full_name = $conn->real_escape_string(trim($_POST['full_name']));
    $phone_number = $conn->real_escape_string(trim($_POST['phone_number']));
    $organization = $conn->real_escape_string(trim($_POST['organization']));
    $area_of_specialization = $conn->real_escape_string(trim($_POST['area_of_specialization']));

    // Prepare and bind SQL statement
    $stmt = $conn->prepare("INSERT INTO members (full_name, phone_number, organization, area_of_specialization) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $full_name, $phone_number, $organization, $area_of_specialization);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        echo "Data recorded successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
}

// Close the database connection
$conn->close();
?>