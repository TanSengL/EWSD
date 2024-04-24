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

$sql = "SELECT * FROM tutors WHERE id = $id";
$result = mysqli_query($data, $sql);
$info = mysqli_fetch_assoc($result);

// Fetch notifications for the tutor
$stmt_notifications = mysqli_prepare($data, "SELECT * FROM notifications WHERE student_id=?");
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
    <title>Tutor Dashboard</title>
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
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
    <h1>Tutor Dashboard</h1>
    <nav class="navbar">
        <ul>
            <li><a href="tutordashboard.php">Home</a></li>
            <li><a href="tutor_upload.php">Upload</a></li>
            <li><a href="tutor_coursework.php">Coursework</a></li>
            <li><a href="tutor_appointment.php">Appointment</a></li>
            <li><a href="#message">Message</a></li>
            <li><a href="tutor_student_list.php">Student List</a></li>
            <li><a href="#notification">Notification <?php echo count($notifications) > 0 ? '<span style="background-color: red; color: white; border-radius: 50%; padding: 2px 5px;">' . count($notifications) . '</span>' : ''; ?></a></li>
            <li><a href="tutorprofile.php">Profile</a></li>
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
            <?php
            if (isset($_SESSION['notification_tutor'])) {
                echo $_SESSION['notification_tutor'];
                unset($_SESSION['notification_tutor']);
            }
            ?>
            <div class="notifications">
                <?php if (count($notifications) > 0): ?>
                    <h3>You have <?php echo count($notifications); ?> new notification<?php echo count($notifications) > 1 ? 's' : ''; ?>:</h3>
                    <?php foreach ($notifications as $notification): ?>
    <p><?php echo $notification['message']; ?></p>
    <?php 
        // Mark the notification as seen by updating its status in the database
        $notification_id = $notification['id'];
        $sql_update_notification = "UPDATE notifications SET status = 'seen' WHERE id = ?";
        $stmt_update_notification = mysqli_prepare($data, $sql_update_notification);
        
        if ($stmt_update_notification) {
            mysqli_stmt_bind_param($stmt_update_notification, "i", $notification_id);
            if (mysqli_stmt_execute($stmt_update_notification)) {
                // Notification status updated successfully
            } else {
                echo "Error executing statement: " . mysqli_error($data);
            }
        } else {
            echo "Error preparing statement: " . mysqli_error($data);
        }
    ?>
<?php endforeach; ?>

                <?php else: ?>
                    <p>No new notifications.</p>
                <?php endif; ?>
            </div>
        </div>
        <!-- Your existing tutor dashboard content here -->
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
