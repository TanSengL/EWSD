<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <style>
    /* Reset styles */
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f0f0;
      margin: 0;
      padding: 0;
    }

    /* Login container styles */
    .login-container {
      max-width: 400px;
      margin: 100px auto;
      background-color: #fff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* Logo styles */
    .logo {
      text-align: center;
      margin-bottom: 30px;
    }

    .logo h2 {
      margin: 0;
      font-size: 24px;
      color: #333;
    }

    /* Form styles */
    .login-form .form-group {
      margin-bottom: 20px;
    }

    .login-form label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
    }

    .login-form input[type="text"],
    .login-form input[type="password"] {
      width: 94%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      transition: border-color 0.3s;
    }

    .login-form input[type="text"]:focus,
    .login-form input[type="password"]:focus {
      border-color: #007bff;
      outline: none;
    }

    /* Button styles */
    .login-button {
      width: 100%;
      padding: 12px;
      background-color: #007bff;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .login-button:hover {
      background-color: #0056b3;
    }

    /* Link styles */
    .login-link {
      text-align: center;
      margin-top: 20px;
    }

    .login-link a {
      text-decoration: none;
      color: #007bff;
    }

    .login-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="logo">
      <h2>eTutoring Login</h2>
    </div>
    <form class="login-form" action="loginpage.php" method="POST">
      <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
      </div>
      <button type="submit" class="login-button">Login</button>
    </form>
  </div>
</body>
</html>

<?php
session_start();

if (isset($_POST['logout'])) {
    unset($_SESSION["username"]);
    unset($_SESSION["user_id"]); // Clear the user_id as well
    session_destroy();
    header("Location: loginpage.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {
    $con = mysqli_connect('localhost', 'root', '', 'ewsd') or die('Unable To connect');
    
    // Prevent SQL Injection
    $username = mysqli_real_escape_string($con, $_POST["username"]);
    $password = mysqli_real_escape_string($con, $_POST["password"]);
    
    // Check in students table
    $stmt = mysqli_prepare($con, "SELECT * FROM students WHERE username=?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        // Direct password comparison
        if ($_POST["password"] == $row["password"]) {
            $_SESSION["username"] = $_POST["username"];
            $_SESSION["user_id"] = $row["id"]; // Set the user_id in session
            echo "<script>alert('Student login successful!'); window.location.href='Studentdashboard.php';</script>";
            exit();
        } else {
            echo "<script>alert('Incorrect Password!'); window.location.href='loginpage.php';</script>";
            exit();
        }
    }
    
    // Check in admins table
    $stmt = mysqli_prepare($con, "SELECT * FROM admins WHERE username=?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        // Direct password comparison
        if ($_POST["password"] == $row["password"]) {
            $_SESSION["username"] = $_POST["username"];
            $_SESSION["user_id"] = $row["id"]; // Set the user_id in session
            echo "<script>alert('Admin login successful!'); window.location.href='admindashboard.php';</script>";
            exit();
        } else {
            echo "<script>alert('Incorrect Password!'); window.location.href='loginpage.php';</script>";
            exit();
        }
    }
    
    // Check in tutors table
    $stmt = mysqli_prepare($con, "SELECT * FROM tutors WHERE username=?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        // Direct password comparison
        if ($_POST["password"] == $row["password"]) {
            $_SESSION["username"] = $_POST["username"];
            $_SESSION["user_id"] = $row["id"]; // Set the user_id in session
            echo "<script>alert('Tutor login successful!'); window.location.href='tutor_dashboard.php';</script>";
            exit();
        } else {
            echo "<script>alert('Incorrect Password!'); window.location.href='loginpage.php';</script>";
            exit();
        }
    }

    echo "<script>alert('Username not found!'); window.location.href='loginpage.php';</script>";
    exit();
}
?>
