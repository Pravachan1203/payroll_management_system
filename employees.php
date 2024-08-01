<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Include database connection file
include 'dbconnect.php';

// Function to delete an employee
function deleteEmployee($conn, $id) {
    $id = $conn->real_escape_string($id);
    $sql = "DELETE FROM employees WHERE id = '$id'";
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Handle delete action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    if (deleteEmployee($conn, $_POST['delete_id'])) {
        header("Location: employees.php");
        exit();
    } else {
        $error = "Error deleting employee.";
    }
}

// Query to fetch all employees
$sql = "SELECT id, first_name, last_name, email, phone, hire_date, job_title FROM employees";
$result = $conn->query($sql);

// Initialize an array to store employee data
$employees = [];

if ($result->num_rows > 0) {
    // Fetch each row from the result set
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employees List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FFE6E6; /* Lightest color */
            color: #7469B6; /* Dark purple */
            margin: 0;
            padding: 0;
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
        table {
            width: 90%;
            margin: 20px auto; /* Center align the table */
            border-collapse: collapse;
            background-color: white;
            border: 1px solid #7469B6; /* Dark purple */
        }
        table, th, td {
            border: 1px solid #7469B6; /* Dark purple */
            padding: 8px;
        }
        th {
            background-color: #AD88C6; /* Medium purple */
            color: white;
        }
        tr:nth-child(even) {
            background-color: #E1AFD1; /* Light purple */
        }
        tr:hover {
            background-color: #7469B6; /* Dark purple */
            color: white;
        }
        td a {
            color: #7469B6; /* Dark purple */
            text-decoration: none;
        }
        td a:hover {
            text-decoration: underline;
        }
        button[type="submit"] {
            background-color: #AD88C6; /* Medium purple */
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
        }
        button[type="submit"]:hover {
            background-color: #7469B6; /* Dark purple */
        }
        form {
            display: inline; /* Display delete form inline */
        }
        .error-message {
            color: red;
            text-align: center; /* Center align the error message */
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <h2>Employees List</h2>
    <p><a href="dashboard.php">Back to Dashboard</a></p>
    <?php
    if (!empty($error)) {
        echo '<p class="error-message">' . $error . '</p>';
    }
    ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Hire Date</th>
                <th>Job Title</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employees as $employee): ?>
                <tr>
                    <td><?php echo $employee['id']; ?></td>
                    <td><a href="view_employee.php?id=<?php echo $employee['id']; ?>"><?php echo $employee['first_name']; ?></a></td>
                    <td><a href="view_employee.php?id=<?php echo $employee['id']; ?>"><?php echo $employee['last_name']; ?></a></td>
                    <td><?php echo $employee['email']; ?></td>
                    <td><?php echo $employee['phone']; ?></td>
                    <td><?php echo $employee['hire_date']; ?></td>
                    <td><?php echo $employee['job_title']; ?></td>
                    <td>
                        <a href="edit_employee.php?id=<?php echo $employee['id']; ?>">Edit</a>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return confirm('Are you sure you want to delete this employee?');">
                            <input type="hidden" name="delete_id" value="<?php echo $employee['id']; ?>">
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
