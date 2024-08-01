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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data and sanitize
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $hire_date = $conn->real_escape_string($_POST['hire_date']);
    $job_title = $conn->real_escape_string($_POST['job_title']);

    // Validate input (basic validation example)
    if (empty($first_name) || empty($last_name) || empty($email) || empty($phone) || empty($hire_date) || empty($job_title)) {
        $error = "All fields are required.";
    } else {
        // Update employee in database
        $sql = "UPDATE employees SET first_name = '$first_name', last_name = '$last_name', 
                email = '$email', phone = '$phone', hire_date = '$hire_date', job_title = '$job_title'
                WHERE id = '$id'";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Employee updated successfully'); window.location.href = 'employees.php';</script>";
            exit();
        } else {
            $error = "Error updating record: " . $conn->error;
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Employee</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FFE6E6; /* Lightest color */
            color: #7469B6; /* Dark purple */
            margin: 0;
            padding: 0;
            text-align: center; /* Center align all content */
        }
        h2 {
            background-color: #AD88C6; /* Medium purple */
            color: white;
            padding: 10px;
            text-align: center; /* Center align the heading */
        }
        p {
            text-align: center; /* Center align the back link */
            margin-top: 10px;
        }
        form {
            width: 300px;
            margin: 0 auto; /* Center align the form */
            background-color: #E1AFD1; /* Light purple */
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        form div {
            margin-bottom: 10px;
            text-align: left; /* Left align labels */
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="email"],
        input[type="date"] {
            width: calc(100% - 12px);
            padding: 6px;
            border: 1px solid #7469B6; /* Dark purple */
            border-radius: 3px;
        }
        button[type="submit"] {
            background-color: #AD88C6; /* Medium purple */
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 3px;
        }
        button[type="submit"]:hover {
            background-color: #7469B6; /* Dark purple */
        }
        .error-message {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h2>Edit Employee</h2>
    <p><a href="dashboard.php">Back to Dashboard</a></p>
    <?php
    if (!empty($error)) {
        echo '<p class="error-message">' . $error . '</p>';
    }
    ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        <div>
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" required>
        </div>
        <div>
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>
        <div>
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
        </div>
        <div>
            <label for="hire_date">Hire Date:</label>
            <input type="date" id="hire_date" name="hire_date" value="<?php echo htmlspecialchars($hire_date); ?>" required>
        </div>
        <div>
            <label for="job_title">Job Title:</label>
            <input type="text" id="job_title" name="job_title" value="<?php echo htmlspecialchars($job_title); ?>" required>
        </div>
        <div>
            <button type="submit">Update Employee</button>
        </div>
    </form>
</body>
</html>
