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

// Fetch coursework data for the student with student names
$sql = "SELECT studentscourseworks.*, students.name AS student_name FROM studentscourseworks 
        LEFT JOIN students ON studentscourseworks.student_id = students.id 
        WHERE student_id = $id";
$result = mysqli_query($data, $sql);
$studentscourseworks = [];
while ($row = mysqli_fetch_assoc($result)) {
    $studentscourseworks[] = $row;
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

// Fetch coursework with pagination
$sql = "SELECT studentscourseworks.*, students.name AS student_name FROM studentscourseworks 
        LEFT JOIN students ON studentscourseworks.student_id = students.id 
        WHERE student_id = $id LIMIT $start_from, $results_per_page";
$result = mysqli_query($data, $sql);
$studentscourseworks = [];
while ($row = mysqli_fetch_assoc($result)) {
    $studentscourseworks[] = $row;
}

// Fetch total number of records for pagination
$result_count = mysqli_query($data, "SELECT COUNT(*) AS total FROM studentscourseworks WHERE student_id = $id");
$row = mysqli_fetch_assoc($result_count);
$total_pages = ceil($row["total"] / $results_per_page);

// Delete coursework data
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM studentscourseworks WHERE id = ?";
    $stmt = mysqli_prepare($data, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: tutor_coursework.php?page=$page");
        exit();
    } else {
        $message = "Error: " . mysqli_error($data);
    }
}


//Download File
if (isset($_POST['download']) && isset($_POST['id'])) {
    $studentscourseworks_id = $_POST['id'];

    // Retrieve file path from the database based on coursework ID
    $sql = "SELECT file_path FROM studentscourseworks WHERE id = $studentscourseworks_id";
    $result = mysqli_query($data, $sql);
    
    // Check if the query was successful
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $file_path = $row['file_path'];

        // Check if file exists
        if (file_exists($file_path)) {
            // Set headers for file download
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
            header('Content-Length: ' . filesize($file_path));

            // Read the file and output it to the browser
            readfile($file_path);
            exit;
        } else {
            // File not found, redirect back to the page where download was initiated
            header("Location: tutor_coursework.php");
            exit;
        }
    } else {
        // Error occurred while fetching file path
        $message = "Error occurred while fetching file path.";
    }
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
    padding: 10px 20px; /* Adjusted padding */
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


.delete-button {
    background-color: #dc3545;
    color: white;
}

.delete-button:hover {
    background-color: #c82333;
}

.download-button {
    background-color: #007bff;
    color: white;
}

.download-button:hover {
    background-color: #0056b3;
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
    <h2>Coursework List</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Coursework Name</th>
                <th>Student Name</th>
                <th>Comment</th>
                <th>Upload Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Initialize counter variable
            $row_number = ($page - 1) * $results_per_page + 1;
            foreach ($studentscourseworks as $studentscoursework): ?>
                <tr>
                <!-- Output the row number -->
                <td><?php echo $row_number; ?></td>
                <td><?php echo $studentscoursework['coursework_name']; ?></td>
                <td><?php echo $studentscoursework['student_name']; ?></td>
                <td><?php echo $studentscoursework['comment']; ?></td>
                <td><?php echo $studentscoursework['uploaded_at']; ?></td>
                <td>
                    <form method="post" onsubmit="return confirm('Are you sure you want to delete this coursework?');">
                        <input type="hidden" name="id" value="<?php echo $studentscoursework['id']; ?>">
                        <button type="submit" name="delete" class="button delete-button">Delete</button>
                    </form>
                    <form method="post">
                        <input type="hidden" name="id" value="<?php echo $studentscoursework['id']; ?>">
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
