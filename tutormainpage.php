<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tutor Main Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            align-items: center; /* Center items vertically */
            justify-content: space-between; /* Align items to the start and end of the flex container */
        }

        nav ul {
            padding: 0; /* Remove padding */
            margin: 30px; /* Remove margin */
            display: flex; /* Use flexbox for navigation list */
            justify-content: flex-end; /* Align items to the end of the flex container */
        }

        nav ul li {
            list-style: none;
            margin-left: 20px; /* Add margin between list items */
        }

        nav ul li:first-child {
            margin-left: 0; /* Remove extra margin for the first list item */
        }

        nav ul li a {
            text-decoration: none;
            color: #fff;
            font-weight: bold;
        }

        nav ul li a:hover {
            color: #ffd700;
        }

        section {
            padding: 20px;
        }

        footer {
            background-color: #f8f9fa;
            text-align: center;
            padding: 10px 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to Tutor Main Page</h1>
        <nav>
            <ul>
                <li><a href="#about">About</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
    </header>
    <section id="about">
        <h2>About</h2> <hr/>
        <p>Welcome to my tutoring website! I am passionate about helping students achieve their academic goals.</p>
        <p>I specialize in various subjects including Math, Science, and English. With personalized tutoring sessions, I aim to cater to each student's unique learning needs.</p>
        </div>
      </div>
    </div>
    </section>

    <section id="services">
        <h2>Services</h2> <hr/>
        <ul>
            <li>One-on-One Tutoring</li>
            <li>Group Sessions</li>
            <li>Test Preparation</li>
            <li>Homework Help</li>
        </ul>
    </section>

    <section id="contact">
        <h2>Contact</h2> <hr/>
        <p>If you're interested in scheduling a tutoring session or have any inquiries, feel free to contact me:</p>
        <ul>
            <li>Email: tutor@example.com</li>
            <li>Phone: 123-456-7890</li>
        </ul>
    </section>

    <footer>
        <p>&copy; 2024 Tutor Main Page</p>
    </footer>
</body>
</html>
