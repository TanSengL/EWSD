<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$db = "ewsd";

$data = mysqli_connect($host, $user, $password, $db);

if (isset($_POST['logout'])) {
    unset($_SESSION["username"]);
    unset($_SESSION["user_id"]);
    session_destroy();
    header("Location: loginpage.php");
    exit();
}

// Fetch the tutor's ID from the session
$tutor_id = $_SESSION['user_id'];



// Fetch the total number of coursework associated with the tutor's students
$sql_total_courseworks = "SELECT COUNT(*) AS total_courseworks FROM coursework WHERE tutor_id IN (SELECT id FROM tutors WHERE assigned_student_id = $tutor_id)";

$result_total_courseworks = mysqli_query($data, $sql_total_courseworks);
$result_total_courseworks = mysqli_query($data, $sql_total_courseworks);
$row_total_courseworks = mysqli_fetch_assoc($result_total_courseworks);
$total_courseworks = $row_total_courseworks['total_courseworks'];


// Fetch the total number of appointments associated with the tutor's students
$sql_total_appointments = "SELECT COUNT(*) AS total_appointments FROM appointments WHERE tutor_id IN (SELECT id FROM tutors WHERE assigned_student_id = $tutor_id)";
$result_total_appointments = mysqli_query($data, $sql_total_appointments);
$row_total_appointments = mysqli_fetch_assoc($result_total_appointments);
$total_appointments = $row_total_appointments['total_appointments'];

// Fetch the total number of students associated with the tutor
$sql_total_tutors = "SELECT COUNT(*) AS total_tutors FROM tutors WHERE assigned_student_id = $tutor_id";
$result_total_tutors = mysqli_query($data, $sql_total_tutors);
$row_total_tutors = mysqli_fetch_assoc($result_total_tutors);
$total_tutors = $row_total_tutors['total_tutors'];

$id = $_SESSION['user_id'];

$sql = "SELECT * FROM students WHERE id = $id";
$result = mysqli_query($data, $sql);
$info = mysqli_fetch_assoc($result);

// Fetch notifications for the student
$stmt_notifications = mysqli_prepare($data, "SELECT * FROM student_notification WHERE student_id=? AND seen = 0");
mysqli_stmt_bind_param($stmt_notifications, "i", $id);
mysqli_stmt_execute($stmt_notifications);
$result_notifications = mysqli_stmt_get_result($stmt_notifications);


$notifications = [];
while ($row = mysqli_fetch_assoc($result_notifications)) {
    $notifications[] = $row;
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <style type="text/css">
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
            height: 100px; /* Set a fixed height for all cards */
            width: 200px;
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

        .div_deg {
            background-color: #f8f9fa;
            width: 400px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
            padding-top: 50px;
            padding-bottom: 50px;
        }

        .div_deg h1 {
            margin-top: 0;
        }

        .notifications {
            margin-top: 20px;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
        }

        .notifications h3 {
            margin-top: 0;
        }

        .notifications p {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>

<header>
    <h1>Student Dashboard</h1>
    <nav class="navbar">
        <ul>
            <li><a href="studentdashboard.php">Home</a></li>
            <li><a href="student_upload.php">Upload</a></li>
            <li><a href="student_coursework.php">Coursework</a></li>
            <li><a href="student_appointment.php">Appointment</a></li>
            <li><a href="student_appointment_list.php">Appointment List</a></li>
            <li><a href="student-tutor-list.php">Tutor List</a></li>
            <li><a href="studentmessage.php">Message</a></li>
            <li><a href="studentnotification.php">Notification <?php echo count($notifications) > 0 ? '<span style="background-color: red; color: white; border-radius: 50%; padding: 2px 5px;">' . count($notifications) . '</span>' : ''; ?></a></li>
            <li><a href="studentprofile.php">Profile</a></li>
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
        <h2>Total Coursework</h2>
        <a href="student_coursework.php">
            <p><?php echo $total_courseworks; ?></p>
        </a>
    </div>


    <div class="card">
        <h2>Total Appointment</h2>
        <a href="student_appointment_list.php">
        <p><?php echo $total_appointments; ?></p>
        </a>
    </div>
    
    <div class="card">
        <h2>Total Tutor</h2>
        <a href="student-tutor-list.php">
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



