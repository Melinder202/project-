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
$result = $conn->query("SELECT * FROM members WHERE id = $id");
$row = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $phone_number = $_POST['phone_number'];
    $organization = $_POST['organization'];
    $area_of_specialization = $_POST['area_of_specialization'];

    $stmt = $conn->prepare("UPDATE members SET full_name=?, phone_number=?, organization=?, area_of_specialization=? WHERE id=?");
    $stmt->bind_param("ssssi", $full_name, $phone_number, $organization, $area_of_specialization, $id);

    if ($stmt->execute()) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Member</title>
</head>
<body>
    <h2>Edit Member</h2>
    <form method="POST">
        <input type="text" name="full_name" value="<?php echo $row['full_name']; ?>" required>
        <input type="text" name="phone_number" value="<?php echo $row['phone_number']; ?>" required>
        <input type="text" name="organization" value="<?php echo $row['organization']; ?>" required>
        <input type="text" name="area_of_specialization" value="<?php echo $row['area_of_specialization']; ?>" required>
        <button type="submit">Update Member</button>
    </form>
</body>
</html>