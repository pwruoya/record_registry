<?php
session_start(); // Ensure session is started

// Include database connection file
require_once '../db.php'; // Adjust the path to your db.php file as needed

// Function to validate user credentials
function validate_user($username, $password) {
    global $conn;

    // Prepare SQL statement
    $stmt = $conn->prepare('SELECT id, password FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verify password
    if ($user && password_verify($password, $user['password'])) {
        return $user; // Return user data including id
    } else {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = validate_user($username, $password);

    if ($user) {
        $_SESSION['user_id'] = $user['id']; // Store user ID in session
        $_SESSION['username'] = $username; // Optionally store username
        header('Location: home.php'); // Redirect to home.php in the same directory
        exit();
    } else {
        $error_message = 'Invalid username or password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .auth-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #f0f0f0 0%, #c8d6e5 100%);
        }

        .auth-card {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .auth-card h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        .auth-card .form-label {
            font-weight: bold;
            color: #555;
        }

        .auth-card .form-control {
            border-radius: 5px;
        }

        .auth-card .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .auth-card .btn-primary:hover {
            background-color: #0069d9;
            border-color: #0056b3;
        }

        .auth-card p {
            margin-top: 15px;
            text-align: center;
        }

        .auth-card p a {
            color: #28a745;
        }

        .auth-card p a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <h1>Login</h1>
            <?php if (isset($error_message)) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            <form action="login.php" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <p>Don't Have an Account? <a href="register.php">Register</a></p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
