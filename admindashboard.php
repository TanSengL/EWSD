<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$db = "ewsd";

$data = mysqli_connect($host, $user, $password, $db);

if (isset($_POST['logout'])) {
    unset($_SESSION["username"]);
    unset($_SESSION["user_id"]);  // Clear the user_id as well
    session_destroy();
    header("Location: loginpage.php");
    exit();
}

// Fetch admin information based on the current user's session ID
$admin_id = $_SESSION['user_id'];  // Assuming 'user_id' is the correct session variable for the admin ID
$sql = "SELECT * FROM admins WHERE id='$admin_id'";
$result = mysqli_query($data, $sql);
$info = mysqli_fetch_assoc($result);

// Fetch the total number of students
$sql_total_students = "SELECT COUNT(*) AS total_students FROM students";
$result_total_students = mysqli_query($data, $sql_total_students);
$row_total_students = mysqli_fetch_assoc($result_total_students);
$total_students = $row_total_students['total_students'];

// Fetch the total number of students
$sql_total_tutors = "SELECT COUNT(*) AS total_tutors FROM tutors";
$result_total_tutors = mysqli_query($data, $sql_total_tutors);
$row_total_tutors = mysqli_fetch_assoc($result_total_tutors);
$total_tutors = $row_total_tutors['total_tutors'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        header {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar {
            margin-right: 20px;
        }

        .navbar ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .navbar ul li {
            display: inline-block;
            margin-left: 20px;
        }

        .navbar ul li a {
            text-decoration: none;
            color: #fff;
            font-weight: bold;
        }

        .navbar ul li a:hover {
            color: #fff;
            text-decoration: underline;
        }

        .content {
            display: flex;
            justify-content: center; /* Center cards horizontally */
            align-items: center;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 200px;
            padding: 20px;
            margin: 20px;
            text-align: center;
            display: inline-block; /* Display cards inline */
        }

        .card h2 {
            color: #007bff;
            margin-top: 0;
        }

        .card p {
            color: #333;
            font-size: 24px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
        
        <nav class="navbar">
            <ul>
                <li><a href="admindashboard.php">Home</a></li>
                <li><a href="allocate.php">Allocate/Reallocate</a></li>
                <li><a href="studentlist.php">Student List</a></li>
                <li><a href="tutorlist.php">Tutor List</a></li>
                <li><a href="adminprofile.php">Profile</a></li>
                <li>
                    <form id="logoutForm" method="post" action="loginpage.php">
                        <a href="#" class="logout-btn" onclick="logout()">Logout</a>
                        <input type="submit" name="logout" style="display:none;">
                    </form>
                </li>
            </ul>
        </nav>
    </header>

    <div class="content">
    <div class="card">
        <h2>Total Students</h2>
        <a href="studentlist.php">
        <p><?php echo $total_students; ?></p>
        </a>
    </div>
    
    <div class="card">
        <h2>Total Tutors</h2>
        <a href="tutorlist.php">
        <p><?php echo $total_tutors; ?></p>
        </a>
    </div>
</div>


    <script>
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                document.getElementById('logoutForm').submit();
            }
        }
    </script>
</body>
</html>
