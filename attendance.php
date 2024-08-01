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
$employee_id = $attendance_date = $status = '';
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
    $attendance_date = $_POST['attendance_date'];
    $status = $_POST['status'];

    // Validate inputs
    if (empty($employee_id) || empty($attendance_date) || empty($status)) {
        $error = "Please fill in all required fields.";
    } else {
        // Insert attendance record into the database
        $stmt = $conn->prepare("INSERT INTO attendance (employee_id, attendance_date, status) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $employee_id, $attendance_date, $status);
        if ($stmt->execute()) {
            $success = "Attendance record added successfully.";
        } else {
            $error = "Error adding attendance record: " . $conn->error;
        }
        $stmt->close();
    }
}

// Query to fetch all attendance records
$sql = "SELECT attendance.id, employees.first_name, employees.last_name, attendance.attendance_date, attendance.status 
        FROM attendance 
        JOIN employees ON attendance.employee_id = employees.id";
$result = $conn->query($sql);

// Initialize an array to store attendance data
$attendances = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $attendances[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Attendance Management</title>
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
        form {
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
        }
        form label {
            font-weight: bold;
        }
        form select, form input[type="date"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        form button {
            padding: 10px 20px;
            background-color: #7469B6; /* Dark purple */
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        form button:hover {
            background-color: #AD88C6; /* Medium purple */
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
        <h2>Attendance Management</h2>
        <p><a href="dashboard.php">Back to Dashboard</a></p>
        <?php
        if (!empty($error)) {
            echo '<p style="color:red;">' . $error . '</p>';
        }
        if (!empty($success)) {
            echo '<p style="color:green;">' . $success . '</p>';
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
            </select><br><br>
            
            <label for="attendance_date">Date:</label>
            <input type="date" name="attendance_date" required><br><br>
            
            <label for="status">Status:</label>
            <select name="status" required>
                <option value="">Select Status</option>
                <option value="Present">Present</option>
                <option value="Absent">Absent</option>
            </select><br><br>
            
            <button type="submit">Add Attendance</button>
        </form>
        <br>
        <h3>Existing Attendance Records</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Employee Name</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attendances as $attendance): ?>
                    <tr>
                        <td><?php echo $attendance['id']; ?></td>
                        <td><?php echo htmlspecialchars($attendance['first_name'] . ' ' . $attendance['last_name']); ?></td>
                        <td><?php echo $attendance['attendance_date']; ?></td>
                        <td><?php echo $attendance['status']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
