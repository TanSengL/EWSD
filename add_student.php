<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: loginpage.php");
    exit();
}

$con = mysqli_connect('localhost', 'root', '', 'ewsd') or die('Unable To connect');

// Add student data if form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($con, $_POST["name"]);
    $age = mysqli_real_escape_string($con, $_POST["age"]);
    $email = mysqli_real_escape_string($con, $_POST["email"]);
    $username = mysqli_real_escape_string($con, $_POST["username"]);
    $password = mysqli_real_escape_string($con, $_POST["password"]);

    $sql = "INSERT INTO students (name, age, email, username, password) VALUES ('$name', '$age', '$email', '$username', '$password')";
    
    if (mysqli_query($con, $sql)) {
        echo "<script>alert('New student added successfully!'); window.location.href='studentlist.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
    }
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
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
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
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="number"],
        input[type="email"],
        input[type="password"] {
            width: 96%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
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
		
		.back-button {
			width: 93.5%;
			display: inline-block;
			margin-top: 10px;
			padding: 10px 20px;
			background-color: #dc3545;
			color: white;
			text-decoration: none;
			border-radius: 5px;
			transition: background-color 0.3s;
			text-align:center;
		}

		.back-button:hover {
			background-color: #c82333;
		}

    </style>
</head>
<body>
    <div class="container">
        <h2>Add New Student</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Add Student</button>
			<a href="studentlist.php" class="back-button">Back to Student List</a>
        </form>
    </div>
</body>
</html>
