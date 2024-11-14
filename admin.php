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

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for adding members
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $full_name = $_POST['full_name'];
    $phone_number = $_POST['phone_number'];
    $organization = $_POST['organization'];
    $area_of_specialization = $_POST['area_of_specialization'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO members (full_name, phone_number, organization, area_of_specialization) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $full_name, $phone_number, $organization, $area_of_specialization);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Error adding record: " . $stmt->error;
    }
}

// Handle form submission for editing members
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $full_name = $_POST['full_name'];
    $phone_number = $_POST['phone_number'];
    $organization = $_POST['organization'];
    $area_of_specialization = $_POST['area_of_specialization'];

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE members SET full_name=?, phone_number=?, organization=?, area_of_specialization=? WHERE id=?");
    $stmt->bind_param("ssssi", $full_name, $phone_number, $organization, $area_of_specialization, $id);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }
}

// Fetch data for display
$result = $conn->query("SELECT * FROM members");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px;
        }
        th, td { 
            padding: 10px; 
            text-align: left; 
            border-bottom: 1px solid #ddd; 
        }
        th { 
            background-color: #007BFF; 
            color: white; 
        }
        tr:hover { 
            background-color: #f1f1f1; 
        }
        .btn {
            padding: 5px 10px;
            margin-left: 5px;
            color: white;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
        }
        .edit { background-color: #28a745; } /* Green for Edit */
        .delete { background-color: #dc3545; } /* Red for Delete */
        .add { background-color: #28a745; } /* Green for Add */
        .btn-container {
            margin-bottom: 20px;
        }
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgba(0,0,0,0.4); 
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 80%; 
            max-width: 500px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>Members List</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Phone Number</th>
            <th>Organization</th>
            <th>Area of Specialization</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['full_name']; ?></td>
            <td><?php echo $row['phone_number']; ?></td>
            <td><?php echo $row['organization']; ?></td>
            <td><?php echo $row['area_of_specialization']; ?></td>
            <td>
                <button class="btn edit" onclick="openEditModal(<?php echo $row['id']; ?>, '<?php echo $row['full_name']; ?>', '<?php echo $row['phone_number']; ?>', '<?php echo $row['organization']; ?>', '<?php echo $row['area_of_specialization']; ?>')">Edit</button>
                <button class="btn delete" onclick="openDeleteModal(<?php echo $row['id']; ?>)">Delete</button>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Buttons for adding and downloading below the table -->
    <div class="btn-container">
        <button class="btn add" onclick="openAddModal()">Add New Member</button>
        <a href="download.php" class="btn" style="background-color: #007BFF;">Download Member List</a>
    </div>

    <!-- Add Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddModal()">&times;</span>
            <h2>Add New Member</h2>
            <form id="addForm" method="POST">
                <input type="hidden" name="action" value="add">
                <input type="text" name="full_name" placeholder="Full Name" required>
                <input type="text" name="phone_number" placeholder="Phone Number" required>
                <input type="text" name="organization" placeholder="Organization" required>
                <input type="text" name="area_of_specialization" placeholder="Area of Specialization" required>
                <button type="submit">Add Member</button>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Edit Member</h2>
            <form id="editForm" method="POST">
                <input type="hidden" name="id" id="editId" required>
                <input type="text" name="full_name" id="editFullName" placeholder="Full Name" required>
                <input type="text" name="phone_number" id="editPhoneNumber" placeholder="Phone Number" required>
                <input type="text" name="organization" id="editOrganization" placeholder="Organization" required>
                <input type="text" name="area_of_specialization" id="editAreaOfSpecialization" placeholder="Area of Specialization" required>
                <button type="submit">Update Member</button>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeDeleteModal()">&times;</span>
            <h2>Are you sure you want to delete this member?</h2>
            <button id="confirmDelete">Yes, Delete</button>
            <button onclick="closeDeleteModal()">Cancel</button>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('addModal').style.display = "block";
        }

        function closeAddModal() {
            document.getElementById('addModal').style.display = "none";
        }

        function openEditModal(id, fullName, phoneNumber, organization, areaOfSpecialization) {
            document.getElementById('editId').value = id;
            document.getElementById('editFullName').value = fullName;
            document.getElementById('editPhoneNumber').value = phoneNumber;
            document.getElementById('editOrganization').value = organization;
            document.getElementById('editAreaOfSpecialization').value = areaOfSpecialization;
            document.getElementById('editModal').style.display = "block";
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = "none";
        }

        function openDeleteModal(id) {
            document.getElementById('deleteModal').style.display = "block";
            document.getElementById('confirmDelete').onclick = function() {
                window.location.href = 'delete.php?id=' + id; // Adjust this to your delete logic
            };
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = "none";
        }

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            if (event.target == document.getElementById('addModal')) {
                closeAddModal();
            }
            if (event.target == document.getElementById('editModal')) {
                closeEditModal();
            }
            if (event.target == document.getElementById('deleteModal')) {
                closeDeleteModal();
            }
        };
    </script>
</body>
</html>