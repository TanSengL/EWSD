<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$db = "ewsd";

$con = mysqli_connect($host, $user, $password, $db);

if (isset($_POST['logout'])) {
    unset($_SESSION["username"]);
    unset($_SESSION["user_id"]);
    session_destroy();
    header("Location: loginpage.php");
    exit();
}

$id = $_SESSION['user_id'];

$message = "";

$sql = "SELECT * FROM students WHERE assigned_tutor_id = $id";
$result = mysqli_query($con, $sql);
$info = mysqli_fetch_assoc($result);

// Fetch notifications for the tutor
$stmt_notifications = mysqli_prepare($con, "SELECT * FROM tutor_notification WHERE tutor_id=? AND seen = 0");
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
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

// Calculate the starting point of the results
$start_from = ($page - 1) * $results_per_page;

// Fetch tutors with pagination
$sql = "SELECT * FROM students WHERE assigned_tutor_id = $id LIMIT $start_from, $results_per_page";
$result = mysqli_query($con, $sql);
$tutors = [];
while ($row = mysqli_fetch_assoc($result)) {
    $tutors[] = $row;
}

// Fetch total number of records for pagination
$result_count = mysqli_query($con, "SELECT COUNT(*) AS total FROM students");
$row = mysqli_fetch_assoc($result_count);
$total_pages = ceil($row["total"] / $results_per_page);

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

        /* Set width for columns */
        .id-column {
            width: 20%;
        }

        .student-name-column {
            width: 30%;
        }

        .student-age-column {
            width: 30%;
        }

        .student-email-column {
            width: 30%;
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

        h2 {
            text-align: center;
        }


    </style>
</head>
<body>

<header>
    <h1>Tutor Dashboard</h1>
    <nav class="navbar">
        <ul>
            <li><a href="tutor_dashboard.php">Home</a></li>
            <li><a href="tutor_upload.php">Upload</a></li>
            <li><a href="tutor_coursework.php">Coursework</a></li>
			<li><a href="tutor_student_list.php">Student List</a></li>
            <li><a href="tutor_appointment.php">Appointment</a></li>
            <li><a href="tutormessage.php">Message</a></li>
            <li><a href="tutornotification.php">Notification <?php echo count($notifications) > 0 ? '<span style="background-color: red; color: white; border-radius: 50%; padding: 2px 5px;">' . count($notifications) . '</span>' : ''; ?></a></li>
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

<div class="container">
    <h2>Students List</h2>
    
    <table>
        <thead>
            <tr>
                <th class="id-column">No</th> 
                <th class="student-name-column">Name</th>
                <th class="student-age-column">Age</th>
                <th class="student-email-column">Email</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Initialize counter variable
            $row_number = ($page - 1) * $results_per_page + 1;
            foreach ($tutors as $students): ?>
                <tr>
                    <!-- Output the row number -->
                    <td><?php echo $row_number; ?></td> <!-- Replaced with row number -->
                    <td class="student-name-column"><?php echo $students['name']; ?></td>
                    <td class="student-age-column"><?php echo $students['age']; ?></td>
                    <td class="student-email-column"><?php echo $students['email']; ?></td>
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
