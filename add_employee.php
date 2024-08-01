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
$first_name = $last_name = $email = $phone = $hire_date = $job_title = '';
$error = '';
$success = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data and sanitize
    $first_name = isset($_POST['first_name']) ? $conn->real_escape_string($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? $conn->real_escape_string($_POST['last_name']) : '';
    $email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? $conn->real_escape_string($_POST['phone']) : '';
    $hire_date = isset($_POST['hire_date']) ? $conn->real_escape_string($_POST['hire_date']) : '';
    $job_title = isset($_POST['job_title']) ? $conn->real_escape_string($_POST['job_title']) : '';

    // Validate input (basic validation example)
    if (empty($first_name) || empty($last_name) || empty($email) || empty($phone) || empty($hire_date) || empty($job_title)) {
        $error = "All fields are required.";
    } elseif (!preg_match('/^\d{10}$/', $phone)) {
        $error = "Phone number must be 10 digits.";
    } else {
        // Insert into database
        $sql = "INSERT INTO employees (first_name, last_name, email, phone, hire_date, job_title) 
                VALUES ('$first_name', '$last_name', '$email', '$phone', '$hire_date', '$job_title')";
        if ($conn->query($sql) === TRUE) {
            $success = "Employee added successfully.";
        } else {
            $error = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Employee</title>
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
        input[type="date"],
        select {
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
        .success-message {
            color: green;
            margin-bottom: 10px;
        }
    </style>
    <script>
        function showAlert(message, type) {
            alert(message);
        }

        document.addEventListener("DOMContentLoaded", function() {
            <?php if (!empty($error)) { ?>
                showAlert("<?php echo $error; ?>", "error");
            <?php } elseif (!empty($success)) { ?>
                showAlert("<?php echo $success; ?>", "success");
            <?php } ?>
        });
    </script>
</head>
<body>
    <h2>Add Employee</h2>
    <p><a href="dashboard.php">Back to Dashboard</a></p>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
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
            <select id="job_title" name="job_title" required>
                <option value="">Select Job Title</option>
                <option value="Software Developer">Software Developer</option>
                <option value="Web Developer">Web Developer</option>
                <option value="Mobile App Developer">Mobile App Developer</option>
                <option value="Database Administrator">Database Administrator</option>
                <option value="System Analyst">System Analyst</option>
                <option value="DevOps Engineer">DevOps Engineer</option>
                <option value="Data Scientist">Data Scientist</option>
                <option value="Network Engineer">Network Engineer</option>
                <option value="Software Tester">Software Tester</option>
                <option value="Project Manager">Project Manager</option>
            </select>
        </div>
        <div>
            <button type="submit">Add Employee</button>
        </div>
    </form>
</body>
</html>
