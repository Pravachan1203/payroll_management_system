<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Include database connection file
include 'dbconnect.php';

// Query to fetch all payroll records
$sql = "SELECT * FROM payroll";
$result = $conn->query($sql);

// Initialize an array to store payroll data
$payrolls = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $payrolls[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Salary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FFE6E6; /* Lightest color */
            color: #7469B6; /* Dark purple */
            margin: 0;
            padding: 0;
        }
        h2 {
            text-align: center;
            color: #7469B6; /* Dark purple */
        }
        .content {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }
        .content a {
            color: #7469B6; /* Dark purple */
            text-decoration: none;
        }
        .content a:hover {
            text-decoration: underline;
        }
        table {
            width: 100%;
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
    <div class="content">
        <h2>View Salary</h2>
        <p><a href="dashboard.php">Back to Dashboard</a></p>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Employee ID</th>
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
                        <td><?php echo $payroll['employee_id']; ?></td>
                        <td><?php echo $payroll['salary']; ?></td>
                        <td><?php echo $payroll['bonus']; ?></td>
                        <td><?php echo $payroll['deductions']; ?></td>
                        <td><?php echo $payroll['month']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
