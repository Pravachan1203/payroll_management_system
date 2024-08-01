<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Include database connection file
include 'dbconnect.php';

// Initialize variables
$id = $first_name = $last_name = $email = $phone = $hire_date = $job_title = '';
$error = '';

// Check if ID is provided in the URL
if (isset($_GET['id']) && !empty(trim($_GET['id']))) {
    // Sanitize and store ID
    $id = $conn->real_escape_string($_GET['id']);

    // Retrieve employee details from database
    $sql = "SELECT * FROM employees WHERE id = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch employee data
        $row = $result->fetch_assoc();
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $email = $row['email'];
        $phone = $row['phone'];
        $hire_date = $row['hire_date'];
        $job_title = $row['job_title'];
    } else {
        $error = "Employee not found.";
    }
} else {
    $error = "Invalid request.";
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Employee</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FFE6E6; /* Lightest color */
            color: #7469B6; /* Dark purple */
            margin: 0;
            padding: 0;
        }
        .employee-details {
            width: 50%;
            margin: 50px auto;
            background-color: #E1AFD1; /* Light purple */
            border: 1px solid #AD88C6; /* Medium purple */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .employee-details h2 {
            text-align: center;
            background-color: #AD88C6; /* Medium purple */
            color: white;
            padding: 10px;
            border-radius: 8px 8px 0 0;
        }
        .employee-details p {
            margin: 10px 0;
        }
        .employee-details label {
            font-weight: bold;
        }
        .employee-details .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .employee-details .back-link a {
            color: #7469B6; /* Dark purple */
            text-decoration: none;
            padding: 10px 20px;
            border: 1px solid #7469B6;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }
        .employee-details .back-link a:hover {
            background-color: #7469B6; /* Dark purple */
            color: white;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="employee-details">
        <h2>Employee Details</h2>
        <?php
        if (!empty($error)) {
            echo '<p class="error-message">' . $error . '</p>';
        } else {
        ?>
        <p><label>First Name:</label> <?php echo htmlspecialchars($first_name); ?></p>
        <p><label>Last Name:</label> <?php echo htmlspecialchars($last_name); ?></p>
        <p><label>Email:</label> <?php echo htmlspecialchars($email); ?></p>
        <p><label>Phone:</label> <?php echo htmlspecialchars($phone); ?></p>
        <p><label>Hire Date:</label> <?php echo htmlspecialchars($hire_date); ?></p>
        <p><label>Job Title:</label> <?php echo htmlspecialchars($job_title); ?></p>
        <div class="back-link">
            <a href="employees.php">Back to Employees List</a>
        </div>
        <?php
        }
        ?>
    </div>
</body>
</html>
