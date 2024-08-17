<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Register</h1>
    <form action="register.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder="Enter Username"required>
        <label for="email">Email:</label> <!-- Added email field -->
        <input type="email" id="email" name="email" placeholder="Enter Email" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter Password"required>
        <button type="submit">Register</button>
        <p>Already Have an Account? <a href="login.php">Login</a></p>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require_once "../db.php";

        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Check if the email already exists
        $checkEmailQuery = "SELECT * FROM users WHERE email='$email'";
        $result = $conn->query($checkEmailQuery);

        if ($result->num_rows > 0) {
            echo "Error: The email address is already registered.";
        } else {
            $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
            if ($conn->query($sql) === TRUE) {
                // Redirect to login page after successful registration
                header("Location: login.php");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        $conn->close();
    }
    ?>
</body>
</html>
