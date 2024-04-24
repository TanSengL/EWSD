<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: loginpage.php");
    exit();
}

if (isset($_POST['logout'])) {
    unset($_SESSION["username"]);
    session_destroy();
    header("Location: loginpage.php");
    exit();
}

$con = mysqli_connect('localhost', 'root', '', 'ewsd') or die('Unable To connect');

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

// Fetch students with pagination
$result = mysqli_query($con, "SELECT * FROM students LIMIT $start_from, $results_per_page");
$students = [];
while ($row = mysqli_fetch_assoc($result)) {
    $students[] = $row;
}

// Fetch total number of records for pagination
$result_count = mysqli_query($con, "SELECT COUNT(*) AS total FROM students");
$row = mysqli_fetch_assoc($result_count);
$total_pages = ceil($row["total"] / $results_per_page);

// Delete student data
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM students WHERE id = $id";
    
    if (mysqli_query($con, $sql)) {
        header("Location: studentlist.php?page=$page");
        exit();
    } else {
        echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
    }
}

// Function to get tutor name by ID
function getTutorName($con, $tutorId) {
    $sql = "SELECT name FROM tutors WHERE id = $tutorId";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['name'];
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

        /* Table styles */
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

        /* Add button styles */
        .add-button {
            margin-top: 20px;
			text-align: center;
        }

        .add-button a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .add-button a:hover {
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
		
		h2{
			text-align:center;
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
<div class="container">
    <h2>Student List</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Age</th>
                <th>Email</th>
                <th>Username</th>
                <th>Password</th>
                <th>Tutor Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?php echo $student['id']; ?></td>
                    <td><?php echo $student['name']; ?></td>
                    <td><?php echo $student['age']; ?></td>
                    <td><?php echo $student['email']; ?></td>
                    <td><?php echo $student['username']; ?></td>
                    <td><?php echo $student['password']; ?></td>
                    <td><?php echo getTutorName($con, $student['assigned_tutor_id']); ?></td>
                    <td>
                        <form method="post" onsubmit="return confirm('Are you sure you want to delete this student?');">
                                                        <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
                            <input type="submit" name="delete" value="Delete" class="delete-button">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="add-button">
        <a href="add_student.php">Add New Student</a>
    </div>
    
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






