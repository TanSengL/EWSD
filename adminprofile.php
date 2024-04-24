<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$db = "ewsd";

$data = mysqli_connect($host, $user, $password, $db);

if (isset($_POST['logout'])) {
    unset($_SESSION["username"]);
    unset($_SESSION["user_id"]);  // Clear the user_id as well
    session_destroy();
    header("Location: loginpage.php");
    exit();
}

$admin_id = $_SESSION['user_id'];  // Retrieve admin ID from session

$sql = "SELECT * FROM admins WHERE id='$admin_id'";
$result = mysqli_query($data, $sql);
$info = mysqli_fetch_assoc($result);

if(isset($_POST['update-profile'])) {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $admin_id = $_POST['admin_id']; // Retrieve admin ID from the form

    $sql2 = "UPDATE admins SET name='$name', age='$age', email='$email', username='$username', password='$password' WHERE id='$admin_id'";
    $result2 = mysqli_query($data, $sql2);

    if($result2) {
        // Update $info with the newly submitted data
        $info['name'] = $name;
        $info['age'] = $age;
        $info['email'] = $email;
        $info['username'] = $username;
        $info['password'] = $password;

        echo "<script>alert('Profile Updated Successfully!'); window.location.href='adminprofile.php';</script>";
    } else {
        echo "<script>alert('Profile Not Updated!'); window.location.href='adminprofile.php';</script>";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
                    <input type="hidden" name="admin_id" value="<?php echo $info['id']; ?>">
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


