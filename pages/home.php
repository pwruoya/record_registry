<?php
session_start(); // Ensure session is started

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Include database connection file
require_once '../db.php'; // Adjust the path to your db.php file as needed

// Fetch user data from the database
$stmt = $conn->prepare('SELECT username FROM users WHERE id = ?');
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../css/style.css"> <!-- Link to your stylesheet -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Bootstrap CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .navbar {
            display: flex;
            justify-content: center;
            background-color: #007bff;
            padding: 10px;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            font-weight: bold;
        }
        .navbar a.active {
            background-color: #0056b3;
        }
        .navbar a:hover {
            background-color: #0056b3;
        }
        .container {
            text-align: center;
            margin-top: 20px;
        }
        .container h1 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <a href="home.php" class="active">Home</a>
        <a href="add_record.php">Add Record</a>
        <a href="view_records.php">View Records</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h1>
        <p>Use the navigation bar above to add or view records.</p>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
