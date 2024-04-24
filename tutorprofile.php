<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$db = "ewsd";

$data = mysqli_connect($host, $user, $password, $db);

if (isset($_POST['logout'])) {
    unset($_SESSION["username"]);
    session_destroy();
    header("Location: loginpage.php");
    exit();
}

// Fetch tutor information based on the current user's session ID
$tutor_id = $_SESSION['user_id'];  // Assuming 'user_id' is the correct session variable for the tutor ID
$sql = "SELECT * FROM tutors WHERE id='$tutor_id'";
$result = mysqli_query($data, $sql);
$info = mysqli_fetch_assoc($result);

$message = "";

// Fetch notifications for the tutor
$stmt_notifications = mysqli_prepare($data, "SELECT * FROM tutor_notification WHERE tutor_id=? AND seen = 0");
mysqli_stmt_bind_param($stmt_notifications, "i", $id);
mysqli_stmt_execute($stmt_notifications);
$result_notifications = mysqli_stmt_get_result($stmt_notifications);

$notifications = []; 
while ($row = mysqli_fetch_assoc($result_notifications)) {
    $notifications[] = $row; 
}




if(isset($_POST['update-profile'])) {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $tutor_id = $_POST['tutor_id']; 

    $sql2 = "UPDATE tutors SET name='$name', age='$age', email='$email', username='$username', password='$password' WHERE id='$tutor_id'";
    $result2 = mysqli_query($data, $sql2);

    if($result2) {
        $info['name'] = $name;
        $info['age'] = $age;
        $info['email'] = $email;
        $info['username'] = $username;
        $info['password'] = $password;

        echo "<script>alert('Profile Updated Successfully!'); window.location.href='tutorprofile.php';</script>";
    } else {
        echo "<script>alert('Profile Not Updated!'); window.location.href='tutorprofile.php';</script>";
    }
}
?>

<!-- ... Rest of the HTML code remains the same ... -->


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

        .content{
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

        .readonly-input {
            color: #888; 
            background-color: #f8f8f8; 
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

<div class="content">
    <center>
        <h1>Profile</h1>

        <div class="div_deg">
            <form method="POST">
                <div>
                    <label>Name</label>
                    <input type="text" name="name" value="<?php echo "{$info['name']}"; ?>" required>
                </div>

                <div>
                    <label>Age</label>
                    <input type="number" name="age" value="<?php echo "{$info['age']}"; ?>" required>
                </div>
              
                <div>
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo "{$info['email']}"; ?>" required>
                </div>

                <div>
                    <label>Username</label>
                    <input type="text" name="username" value="<?php echo "{$info['username']}"; ?>" required>
                </div>

                <div>
                    <label>Password</label>
                    <input type="password" name="password" value="<?php echo "{$info['password']}"; ?>" required>
                </div>

                <div>
                    <input type="hidden" name="tutor_id" value="<?php echo $info['id']; ?>">
                    <input type="submit" class="btn btn-primary" name="update-profile" value="Update">
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


