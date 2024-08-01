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
$employee_id = $salary = $bonus = $deductions = $month = '';
$error = '';
$success = '';

// Fetch all employees for the dropdown
$employee_sql = "SELECT id, first_name, last_name FROM employees";
$employee_result = $conn->query($employee_sql);
$employees = [];

if ($employee_result->num_rows > 0) {
    while ($row = $employee_result->fetch_assoc()) {
        $employees[] = $row;
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = $_POST['employee_id'];
    $salary = $_POST['salary'];
    $bonus = $_POST['bonus'];
    $deductions = $_POST['deductions'];
    $month = $_POST['month'];

    // Validate inputs
    if (empty($employee_id) || empty($salary) || empty($month)) {
        $error = "Please fill in all required fields.";
    } else {
        // Insert payroll record into the database
        $stmt = $conn->prepare("INSERT INTO payroll (employee_id, salary, bonus, deductions, month) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iddds", $employee_id, $salary, $bonus, $deductions, $month);
        if ($stmt->execute()) {
            $success = "Salary record added successfully.";
        } else {
            $error = "Error adding salary record: " . $conn->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Salary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FFE6E6; /* Lightest color */
            color: #7469B6; /* Dark purple */
            margin: 0;
            padding: 0;
        }
        .form-container {
            width: 50%;
            margin: 50px auto; /* Center align the form container */
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: white;
        }
        .form-container h2 {
            text-align: center;
            background-color: #AD88C6; /* Medium purple */
            color: white;
            padding: 10px;
            border-radius: 8px 8px 0 0;
        }
        .form-container label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        .form-container input[type="text"], 
        .form-container input[type="number"], 
        .form-container input[type="month"], 
        .form-container select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-container button {
            padding: 10px 20px;
            background-color: #7469B6; /* Dark purple */
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: block;
            width: 100%;
            margin-top: 10px;
        }
        .form-container button:hover {
            background-color: #AD88C6; /* Medium purple */
        }
        .form-container p {
            text-align: center;
            margin-top: 10px;
        }
        .form-container a {
            color: #7469B6; /* Dark purple */
            text-decoration: none;
        }
        .form-container a:hover {
            text-decoration: underline;
        }
        .error-message {
            color: red;
            text-align: center;
        }
        .success-message {
            color: green;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add Salary</h2>
        <?php
        if (!empty($error)) {
            echo '<p class="error-message">' . $error . '</p>';
        }
        if (!empty($success)) {
            echo '<p class="success-message">' . $success . '</p>';
        }
        ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="employee_id">Employee:</label>
            <select name="employee_id" required>
                <option value="">Select Employee</option>
                <?php foreach ($employees as $employee): ?>
                    <option value="<?php echo $employee['id']; ?>">
                        <?php echo htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <label for="salary">Salary:</label>
            <input type="number" step="0.01" name="salary" required>
            
            <label for="bonus">Bonus:</label>
            <input type="number" step="0.01" name="bonus">
            
            <label for="deductions">Deductions:</label>
            <input type="number" step="0.01" name="deductions">
            
            <label for="month">Month:</label>
            <input type="month" name="month" required>
            
            <button type="submit">Add Salary</button>
        </form>
        <p><a href="payroll.php">Back to Payroll</a></p>
    </div>
</body>
</html>
