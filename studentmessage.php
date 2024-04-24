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

// Fetch student messages
$stmt_student_messages = mysqli_prepare($data, "SELECT * FROM student_messages WHERE tutor_id=?");
mysqli_stmt_bind_param($stmt_student_messages, "i", $id);
mysqli_stmt_execute($stmt_student_messages);
$result_student_messages = mysqli_stmt_get_result($stmt_student_messages);

$student_messages = [];
while ($row = mysqli_fetch_assoc($result_student_messages)) {
    $student_messages[] = $row;
}

// Process form submission
if (isset($_POST['message'])) {
    $student_id = $_SESSION['user_id'];
    $message_content = $_POST['message'];

    // Retrieve allocated tutor ID from the students table
    $sql = "SELECT assigned_tutor_id FROM students WHERE id = ?";
    $stmt = mysqli_prepare($data, $sql);
    mysqli_stmt_bind_param($stmt, "i", $student_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $allocated_tutor_id);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($allocated_tutor_id) {
        // Insert the message into the tutor_messages table
        $insert_query = "INSERT INTO tutor_messages (sender_id, receiver_id, message_content, student_id) VALUES (?, ?, ?, ?)";
        $insert_stmt = mysqli_prepare($data, $insert_query);
        mysqli_stmt_bind_param($insert_stmt, "iisi", $student_id, $allocated_tutor_id, $message_content, $student_id);

        if (mysqli_stmt_execute($insert_stmt)) {
            echo "Message sent successfully!";
            // Refresh the page after sending the message
            header("Refresh:0");
        } else {
            echo "Error: " . mysqli_error($data);
        }
        mysqli_stmt_close($insert_stmt);
    } else {
        echo "Error: Tutor ID not found for the student.";
    }
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
            width: 50%;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
            margin-bottom: 20px;
        }

        .div_deg h1 {
            margin-top: 0;
            margin-bottom: 20px;
        }

        .div_deg form div {
            margin-bottom: 15px;
        }

        .div_deg form div label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .div_deg form div textarea {
            width: calc(100% - 10px);
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .div_deg form div input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .div_deg form div input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .message-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .message-table th,
        .message-table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        .message-table th {
            background-color: #007bff;
            color: #fff;
        }

        .message-table tr:nth-child(even) {
            background-color: #f2f2f2;
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
        <h1>Student Messages</h1>
        <div class="div_deg">
            <!-- Display student messages -->
            <?php
            if (count($student_messages) > 0) {
                foreach ($student_messages as $message) {
                    // Fetch the allocated tutor's name
                    $tutor_id = $message['tutor_id'];
                    $tutor_info_query = "SELECT * FROM tutors WHERE id = $tutor_id";
                    $tutor_info_result = mysqli_query($data, $tutor_info_query);
                    $tutor_info = mysqli_fetch_assoc($tutor_info_result);

                    echo "<div class='student-message'>";
                    echo "<p><strong>From:</strong> " . $tutor_info['name'] . "</p>";
                    echo "<p><strong>Message:</strong> {$message['message_content']}</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No messages for students.</p>";
            }
            ?>
        </div>

        <h1>Send Message to Your Tutor</h1>
        <div class="div_deg">
            <!-- Message form -->
            <form method="post">
                <div>
                    <label for="message">Message:</label>
                    <textarea id="message" name="message" rows="4" cols="50" required></textarea>
                </div>
                <input type="submit" value="Send Message">
            </form>
        </div>
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

