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
        header("Location: student_upload.php?page=$page");
        exit();
    } else {
        $message = "Error: " . mysqli_error($data);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $coursework_name = $_POST['courseworkname'];
    $comment = $_POST['comment'];
    $file_name = $_FILES['uploadfile']['name'];
    $file_tmp = $_FILES['uploadfile']['tmp_name'];
    $file_destination = "student_uploads/" . $file_name; // Define your file upload destination

    // Move uploaded file to destination
    if (move_uploaded_file($file_tmp, $file_destination)) {
        // Insert file information into database
        $sql = "INSERT INTO studentscourseworks (student_id, coursework_name, comment, file_path) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($data, $sql);
        mysqli_stmt_bind_param($stmt, "isss", $id, $coursework_name, $comment, $file_destination);
        
        if (mysqli_stmt_execute($stmt)) {
            $message = "Coursework uploaded successfully.";
        } else {
            $message = "Error: " . mysqli_error($data);
        }
    } else {
        $message = "Failed to upload coursework.";
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

        /* Delete button styles */
        .delete-button {
            display: inline-block;
            padding: 5px 10px;
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            margin-left: 5px;
        }

        .delete-button:hover {
            background-color: #c82333;
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

<div class="container">
    <h2>Student Upload Coursework</h2>
    <!-- Display the message -->
    <?php if (!empty($message)) : ?>
        <div class="message" style="margin-bottom: 20px;">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    
    <!-- Coursework upload form -->
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="courseworkname">Coursework Name:</label>
            <input type="text" id="courseworkname" name="courseworkname" placeholder="--Coursework Name--" required >
        </div>
        <div class="form-group">
            <label for="comment">Comment:</label>
            <textarea id="comment" name="comment" rows="6" cols="60" required></textarea>
        </div>
        <div class="form-group">
            <label for="uploadfile">Upload File:</label>
            <input type="file" id="uploadfile" name="uploadfile" accept=".pdf" required>
        </div>
        <button type="submit">Upload</button>
    </form>
</div>

<div>
    <h2>Student Upload List</h2>
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
                        <form method="post" onsubmit="return confirm('Are you sure you want to delete this tutor?');">
                            <input type="hidden" name="id" value="<?php echo $studentscoursework['id']; ?>">
                            <input type="submit" name="delete" value="Delete" class="delete-button">
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
