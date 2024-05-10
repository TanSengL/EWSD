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

$message = "";

$sql = "SELECT coursework.*, tutors.name AS tutor_name 
        FROM coursework
        LEFT JOIN tutors ON coursework.tutor_id = tutors.id 
        WHERE tutors.assigned_student_id = $id";
$result = mysqli_query($data, $sql);
$studentscourseworks = [];
while ($row = mysqli_fetch_assoc($result)) {
    $studentscourseworks[] = $row;
}

// Fetch notifications for the student
$stmt_notifications = mysqli_prepare($data, "SELECT * FROM student_notification WHERE student_id=? AND seen = 0");
mysqli_stmt_bind_param($stmt_notifications, "i", $id);
mysqli_stmt_execute($stmt_notifications);
$result_notifications = mysqli_stmt_get_result($stmt_notifications);


$notifications = [];
while ($row = mysqli_fetch_assoc($result_notifications)) {
    $notifications[] = $row;
}

// Pagination variables
$results_per_page = 8;

// Get current page or set a default
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// Calculate the starting point of the results
$start_from = ($page - 1) * $results_per_page;

// Fetch tutors with pagination
$sql = "SELECT coursework.*, tutors.name AS tutor_name 
FROM coursework
LEFT JOIN tutors ON coursework.tutor_id = tutors.id 
WHERE tutors.assigned_student_id = $id LIMIT $start_from, $results_per_page";
$result = mysqli_query($data, $sql);
$courseworks = [];
while ($row = mysqli_fetch_assoc($result)) {
    $courseworks[] = $row;
}

// Fetch total number of records for pagination
$result_count = mysqli_query($data, "SELECT COUNT(*) AS total FROM coursework WHERE tutor_id = $id");
$row = mysqli_fetch_assoc($result_count);
$total_pages = ceil($row["total"] / $results_per_page);




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

        h2 {
            text-align: center;
            color: #333333;
            margin-bottom: 20px;
        }

        table {
            width: 90%;
            max-width: 1200px;
            border-collapse: collapse;
            margin-top: 20px;
            margin-left: auto;
            margin-right: auto;
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        /* Pagination styles */
        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            display: inline-block;
            padding: 10px;
            margin-right: 5px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .pagination a:hover {
            background-color: #0056b3;
        }

        .pagination .active {
            background-color: #0056b3;
        }

        /* Button styles */
        .button {
            display: block;
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s, box-shadow 0.3s;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .download-button {
            background-color: #007bff;
            color: white;
        }

        .download-button:hover {
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

<div>
    <h2>Coursework List</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Coursework Name</th>
                <th>Tutor Name</th>
                <th>Due Date</th>
                <th>Upload Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Initialize counter variable
            $row_number = ($page - 1) * $results_per_page + 1;
            foreach ($courseworks as $coursework): ?>
                <tr>
                    <!-- Output the row number -->
                    <td><?php echo $row_number; ?></td>
                    <td><?php echo $coursework['coursework_name']; ?></td>
                    <td><?php echo $coursework['tutor_name']; ?></td>
                    <td><?php echo $coursework['due_date']; ?></td>
                    <td><?php echo $coursework['upload_date']; ?></td>
                    <td>
                        <form action="download.php" method="get">
                            <input type="hidden" name="id" value="<?=$coursework['id'];?>">
                            <button type="submit" name="download" class="button download-button">Download</button>
                        </form>
                    </td>
                </tr>
                <?php
                // Increment the row number
                $row_number++;
                ?>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <!-- Pagination -->
    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" <?php if ($i == $page) echo 'class="active"'; ?>><?php echo $i; ?></a>
        <?php endfor; ?>
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
