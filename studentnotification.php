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

// Update notification status to mark them as seen
$update_sql = "UPDATE student_notification SET seen = 1 WHERE student_id = ?";
$update_stmt = mysqli_prepare($data, $update_sql);
mysqli_stmt_bind_param($update_stmt, "i", $id);
mysqli_stmt_execute($update_stmt);


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
            background-color: #f4f4f4;
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

        label {
            display: inline-block;
            text-align: right;
            width: 100px;
            padding-top: 10px;
            padding-bottom: 10px;
            margin-top: 5px;
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

        .content {
            padding-top: 20px;
        }

        .div_deg h1 {
            margin-top: 0;
            text-align: center;
            margin-bottom: 20px;
        }

        .div_deg form div {
            margin-bottom: 15px;
        }

        .div_deg form div label {
            width: 120px;
        }

        .div_deg form div input {
            width: calc(100% - 130px);
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .div_deg form div input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        .div_deg form div input[type="submit"]:hover {
            background-color: #0056b3;
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
    <center>
        <h1>Notifications</h1>
        <div class="div_deg">
    <?php if (count($notifications) > 0): ?>
        <div class="notifications">
            <h3>You have <?php echo count($notifications); ?> new notification<?php echo count($notifications) > 1 ? 's' : ''; ?>:</h3>
            <?php foreach ($notifications as $notification): ?>
                <p><?php echo $notification['message']; ?></p>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No new notifications.</p>
    <?php endif; ?>
</div>
        <!-- Your existing student dashboard content here -->
    </center>
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