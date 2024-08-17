<?php
session_start();

// Include database connection file
require_once '../db.php';

// Function to register a new user
function register_user($username, $email, $password) {
    global $conn;

    // Check if username or email already exists
    $stmt = $conn->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
    $stmt->bind_param('ss', $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return 'Username or email already exists.';
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert new user
    $stmt = $conn->prepare('INSERT INTO users (username, password, email) VALUES (?, ?, ?)');
    $stmt->bind_param('sss', $username, $hashed_password, $email);

    if ($stmt->execute()) {
        return true;
    } else {
        return 'Registration failed: ' . $conn->error;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = register_user($username, $email, $password);

    if ($result === true) {
        header('Location: login.php'); // Redirect to login.php after successful registration
        exit();
    } else {
        $error_message = $result;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
            background-color: #28a745;
            border-color: #28a745;
        }

        .auth-card .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .auth-card p {
            margin-top: 15px;
            text-align: center;
        }

        .auth-card p a {
            color: #007bff;
        }

        .auth-card p a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <h1>Register</h1>
            <?php if (isset($error_message)) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            <form action="register.php" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
            <p>Already Have an Account? <a href="login.php">Login</a></p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
