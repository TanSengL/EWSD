<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$db = "ewsd";

$data = mysqli_connect($host, $user, $password, $db);

$message = "";

if (isset($_POST['allocate'])) {
    $student_id = $_POST['student_id'];
    $tutor_id = $_POST['tutor_id'];

    // Update the student's assigned tutor in the database
    $update_student_sql = "UPDATE students SET assigned_tutor_id = ? WHERE id = ?";
    $update_student_stmt = mysqli_prepare($data, $update_student_sql);
    mysqli_stmt_bind_param($update_student_stmt, "ii", $tutor_id, $student_id);

    // Update the tutor's assigned student in the database
    $update_tutor_sql = "UPDATE tutors SET assigned_student_id = ? WHERE id = ?";
    $update_tutor_stmt = mysqli_prepare($data, $update_tutor_sql);
    mysqli_stmt_bind_param($update_tutor_stmt, "ii", $student_id, $tutor_id);

    // Execute both update statements
    $update_student_result = mysqli_stmt_execute($update_student_stmt);
    $update_tutor_result = mysqli_stmt_execute($update_tutor_stmt);

    if ($update_student_result && $update_tutor_result) {
    // Insert notification for the student
    $notification_student = "You have been allocated a new tutor.";
    $sql_student_notification = "INSERT INTO student_notification (student_id, message) VALUES (?, ?)";
    $stmt_student_notification = mysqli_prepare($data, $sql_student_notification);
    mysqli_stmt_bind_param($stmt_student_notification, "is", $student_id, $notification_student);

    // Insert notification for the tutor
    $notification_tutor = "You have been allocated a new student.";
    $sql_tutor_notification = "INSERT INTO tutor_notification (tutor_id, message) VALUES (?, ?)";
    $stmt_tutor_notification = mysqli_prepare($data, $sql_tutor_notification);
    mysqli_stmt_bind_param($stmt_tutor_notification, "is", $tutor_id, $notification_tutor);

    if (mysqli_stmt_execute($stmt_student_notification) && mysqli_stmt_execute($stmt_tutor_notification)) {
        $message = "Allocation complete!";
    } else {
        echo "Error: " . mysqli_error($data);
    }
} else {
    echo "Error: " . mysqli_error($data);
}

    
}


$students = [];
$tutors = [];

// Fetch students
$sql_students = "SELECT * FROM students";
$result_students = mysqli_query($data, $sql_students);
while ($row = mysqli_fetch_assoc($result_students)) {
    $students[] = $row;
}

// Fetch tutors
$sql_tutors = "SELECT * FROM tutors";
$result_tutors = mysqli_query($data, $sql_tutors);
$tutors = []; // Initialize the $tutors array
while ($row = mysqli_fetch_assoc($result_tutors)) {
    $tutors[] = $row; // Assign fetched row to the $tutors array
}


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

        .content {
            padding: 20px;
        }

        .div_deg {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            margin: 0 auto;
        }

        .div_deg h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .div_deg form div {
            margin-bottom: 15px;
        }

        .div_deg form div label {
            display: inline-block;
            width: 150px;
            font-weight: bold;
        }

        .div_deg form div select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .div_deg form div button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .div_deg form div button:hover {
            background-color: #0056b3;
        }

        .message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
            text-align: center;
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
    <center>
        <h1>Allocate Tutor to Student</h1>
        <div class="div_deg">
            <?php if ($message): ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div>
                    <label for="student">Select Student:</label>
                    <select name="student_id" required>
                        <option value="">Select Student</option>
                        <?php foreach ($students as $student): ?>
                            <option value="<?php echo $student['id']; ?>"><?php echo $student['username']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="tutor">Select Tutor:</label>
                    <select name="tutor_id" required>
                        <option value="">Select Tutor</option>
                        <?php foreach ($tutors as $tutor): ?>
                            <option value="<?php echo $tutor['id']; ?>"><?php echo $tutor['username']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <button type="submit" name="allocate">Allocate</button>
                </div>
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
