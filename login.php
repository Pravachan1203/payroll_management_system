<?php
session_start();

// Include database connection file
include 'dbconnect.php';

// Initialize variables
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from POST request
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    // Query to check if the user exists
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Fetch user data
        $row = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Set session variables
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $row['role'];
            $_SESSION['login_time'] = time(); // Set the login time

            // Redirect to dashboard or another page
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No user found with that username.";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FFE6E6; /* Lightest color */
            color: #7469B6; /* Dark purple */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: url("https://th.bing.com/th/id/OIP.27IYimQG8o8gtJ3i39vAxAHaE8?rs=1&pid=ImgDetMain") center center no-repeat;
            background-size: cover;
        
        }
        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        .login-container h2 {
            color: #7469B6; /* Dark purple */
        }
        .login-container label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }
        .login-container input {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 5px 0 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .login-container button {
            padding: 10px 20px;
            background-color: #7469B6; /* Dark purple */
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        .login-container button:hover {
            background-color: #AD88C6; /* Medium purple */
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php
        if (!empty($error)) {
            echo '<p class="error">' . $error . '</p>';
        }
        ?>
        <form method="post" action="">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <button type="submit">Login</button>
            </div>
        </form>
    </div>
</body>
</html>
