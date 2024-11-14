<?php
session_start();

// Set your desired username and password
$correct_username = "admin"; // Change this to your preferred username
$correct_password = "password123#"; // Change this to your preferred password

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check credentials
    if ($username === $correct_username && $password === $correct_password) {
        $_SESSION['client_logged_in'] = true;
        header("Location: admin.php");
        exit();
    } else {
        $error = "Invalid credentials!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            background-color: #007BFF; /* Blue background */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .login-container {
            background-color: white; /* White background for the form */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 300px;
            transition: all 0.3s ease;
        }

        .login-container:hover {
            background-color: #f8f9fa; /* Light gray on hover */
        }

        h2 {
            text-align: center;
            color: black; /* Black heading */
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF; /* Button color */
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3; /* Darker blue on button hover */
        }

        p {
            color: red; /* Error message color */
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Dashboard</h2>
        <?php if (isset($error)) echo "<p>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>