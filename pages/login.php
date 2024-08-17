<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Login</h1>
    <form action="login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder="Enter Username"required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter Password" required>
        <button type="submit">Login</button>
        <p>Dont Have an Account? <a href="register.php">Register</a></p>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require_once "../db.php";

        $username = $_POST['username'];
        $password = $_POST['password'];

        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $hashed_password);
            $stmt->fetch();
            
            if (password_verify($password, $hashed_password)) {
                session_start();
                $_SESSION['user_id'] = $user_id; // Store user ID in session
                $_SESSION['username'] = $username;
                header("Location: home.php"); // Redirect to a page after login
                exit();
            } else {
                echo "Invalid password";
            }
        } else {
            echo "No user found with that username";
        }

        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>
