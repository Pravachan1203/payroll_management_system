<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
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
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin-bottom: 5px;
        }
        li a {
            display: block;
            padding: 10px;
            background-color: #E1AFD1; /* Light purple */
            color: #7469B6; /* Dark purple */
            text-decoration: none;
        }
        li a:hover {
            background-color: #AD88C6; /* Medium purple */
            color: white;
        }
    </style>
</head>
<body>
    <h2>Payroll</h2>
    <ul>
        <li><a href="add_employee.php">Add Employee</a></li>
        <li><a href="employees.php">View Employees</a></li>
        <li><a href="add_salary.php">Add Salary</a></li>
        <li><a href="view_salary.php">View Salary</a></li>
        <li><a href="attendance.php">Manage Attendance</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</body>
</html>
