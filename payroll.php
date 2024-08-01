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
            $success = "Payroll record added successfully.";
        } else {
            $error = "Error adding payroll record: " . $conn->error;
        }
        $stmt->close();
    }
}

// Query to fetch all payroll records
$sql = "SELECT payroll.id, employees.first_name, employees.last_name, payroll.salary, payroll.bonus, payroll.deductions, payroll.month 
        FROM payroll 
        JOIN employees ON payroll.employee_id = employees.id";
$result = $conn->query($sql);

// Initialize an array to store payroll data
$payrolls = [];

if ($result->num_rows > 0) {
    // Fetch each row from the result set
    while ($row = $result->fetch_assoc()) {
        $payrolls[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payroll Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FFE6E6; /* Lightest color */
            color: #7469B6; /* Dark purple */
            margin: 0;
            padding: 0;
        }
        h2, h3 {
            text-align: center;
            color: #7469B6; /* Dark purple */
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
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #AD88C6; /* Medium purple */
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Payroll Management</h2>
    <p><a href="dashboard.php">Back to Dashboard</a></p>
    <div class="form-container">
        <?php
        if (!empty($error)) {
            echo '<p class="error-message">' . $error . '</p>';
        }
        if (!empty($success)) {
            echo '<p class="success-message">' . $success . '</p>';
        }
        ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="employee_id">Employee ID:</label>
            <input type="number" name="employee_id" required>
            
            <label for="salary">Salary:</label>
            <input type="number" step="0.01" name="salary" required>
            
            <label for="bonus">Bonus:</label>
            <input type="number" step="0.01" name="bonus">
            
            <label for="deductions">Deductions:</label>
            <input type="number" step="0.01" name="deductions">
            
            <label for="month">Month:</label>
            <input type="month" name="month" required>
            
            <button type="submit">Add Payroll</button>
        </form>
    </div>
    <h3>Existing Payroll Records</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Employee Name</th>
                <th>Salary</th>
                <th>Bonus</th>
                <th>Deductions</th>
                <th>Month</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($payrolls as $payroll): ?>
                <tr>
                    <td><?php echo $payroll['id']; ?></td>
                    <td><?php echo $payroll['first_name'] . ' ' . $payroll['last_name']; ?></td>
                    <td><?php echo $payroll['salary']; ?></td>
                    <td><?php echo $payroll['bonus']; ?></td>
                    <td><?php echo $payroll['deductions']; ?></td>
                    <td><?php echo $payroll['month']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
