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

if (isset($_POST['accept_appointment'])) {
    $appointment_id = $_POST['accept_appointment'];
    // Update appointment status to 'Approved' in the database
    $approve_sql = "UPDATE appointments SET status = 'Accepted' WHERE id = $appointment_id";
    mysqli_query($data, $approve_sql);
}


if (isset($_POST['decline_appointment'])) {
    $appointment_id = $_POST['decline_appointment'];
    // Update appointment status to 'Declined' in the database
    $decline_sql = "UPDATE appointments SET status = 'Declined' WHERE id = $appointment_id";
    mysqli_query($data, $decline_sql);
}


$sql = "SELECT appointments.*, students.name AS student_name FROM appointments 
        LEFT JOIN students ON appointments.student_id = students.id 
        WHERE tutor_id = $id";
$result = mysqli_query($data, $sql);


// Check if appointments data is fetched successfully
if ($result) {
    // Initialize appointments array
    $appointments = [];

    // Fetch appointments data into array
    while ($row = mysqli_fetch_assoc($result)) {
        $appointments[] = $row;
    }
} else {
    // Handle query error
    echo "Error fetching appointments: " . mysqli_error($data);
}




// Fetch notifications for the tutor
$stmt_notifications = mysqli_prepare($data, "SELECT * FROM tutor_notification WHERE tutor_id=? AND seen = 0");
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
$sql = "SELECT * FROM appointments WHERE tutor_id = $id LIMIT $start_from, $results_per_page";
$result = mysqli_query($data, $sql);
$courseworks = [];
while ($row = mysqli_fetch_assoc($result)) {
    $courseworks[] = $row;
}

// Fetch total number of records for pagination
$result_count = mysqli_query($data, "SELECT COUNT(*) AS total FROM appointments WHERE tutor_id = $id");
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

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333333;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .form-group label {
            flex: 0 0 150px;
            font-weight: bold;
            margin-right: 10px;
            text-align: right;
        }

        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group input[type="file"] {
            flex: 1;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            box-sizing: border-box;
        }
        
        button[type="submit"] {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
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

        /* Decline button styles */
        .decline-button {
            display: inline-block;
            padding: 5px 10px;
            background-color: #dc3545 !important; /* Red color */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            margin-top: 10px;
        }

        .decline-button:hover {
            background-color: #c82333; /* Darker red on hover */
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

<div>
    <h2>Appointment List</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Student Name</th>
                <th>Date</th>
                <th>Time</th>
                <th>Type</th>
                <th>Reason</th>
                <th>Status</th>
                <th style="width: 20px">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
            // Initialize counter variable
            $row_number = ($page - 1) * $results_per_page + 1;
            foreach ($appointments as $appointment): ?>
                <tr>
                    <!-- Output the row number -->
                    <td><?php echo $row_number; ?></td>
                    <td><?php echo $appointment['student_name']; ?></td>
                    <td><?php echo $appointment['appointment_date']; ?></td>
                    <td><?php echo date("g.i A", strtotime($appointment['appointment_time'])); ?> - <?php echo date("g.i A", strtotime('+1 hour', strtotime($appointment['appointment_time']))); ?></td>
                    <td><?php echo $appointment['appointment_type']; ?></td>
                    <td><?php echo $appointment['reason']; ?></td>
                    <td><?php echo $appointment['status']; ?></td>
                    <td>
                    <form method="post">
                        <button type="submit" name="accept_appointment" value="<?=$appointment['id'];?>">Accept</button>
                        <button type="submit" name="decline_appointment" class="decline-button" value="<?=$appointment['id'];?>">Decline</button>
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
