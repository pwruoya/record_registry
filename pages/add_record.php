<?php
session_start(); // Ensure session is started

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Include database connection file
require_once '../db.php'; // Adjust the path to your db.php file as needed
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Record</title>
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
        .form-container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-container label {
            font-weight: bold;
        }
        .form-container input, .form-container select, .form-container textarea {
            border-radius: 5px;
            margin-bottom: 15px;
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .form-container button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        .form-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <a href="home.php">Home</a>
        <a href="add_record.php" class="active">Add Record</a>
        <a href="view_records.php">View Records</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container">
        <h1>Add New Record</h1>
        <div class="form-container">
            <form action="add_record.php" method="post">
                <label for="record_title">Record Item:</label>
                <input type="text" id="record_title" name="record_title" required>
                
                <label for="record_type">Record Type:</label>
                <select id="record_type" name="record_type" required>
                    <option value="Personal">Personal</option>
                    <option value="Property">Property</option>
                    <option value="Legal">Legal</option>
                    <option value="Other">Other</option>
                </select>
                
                <label for="holder_name">Holder Name:</label>
                <input type="text" id="holder_name" name="holder_name" required>
                
                <label for="identification_number">Identification Number:</label>
                <input type="text" id="identification_number" name="identification_number" required>
                
                <label for="issue_date">Issue Date:</label>
                <input type="date" id="issue_date" name="issue_date" required>
                
                <label for="record_status">Record Status:</label>
                <select id="record_status" name="record_status">
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                    <option value="Expired">Expired</option>
                </select>
                
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" step="any" required>

                <label for="unit">Unit:</label>
                <select id="unit" name="unit" required>
                    <option value="litres">Litres</option>
                    <option value="kilos">Kilos</option>
                    <option value="pieces">Pieces</option>
                    <option value="grams">Grams</option>
                    <option value="meters">Meters</option>
                    <option value="units">Units</option>
                    <option value="other">Other</option>
                </select>
                
                <label for="remarks">Remarks:</label>
                <textarea id="remarks" name="remarks"></textarea>
                
                <button type="submit">Add Record</button>
            </form>

            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $record_title = $_POST['record_title'];
                $record_type = $_POST['record_type'];
                $holder_name = $_POST['holder_name'];
                $identification_number = $_POST['identification_number'];
                $issue_date = $_POST['issue_date'];
                $record_status = $_POST['record_status'];
                $quantity = $_POST['quantity'];
                $unit = $_POST['unit'];
                $remarks = $_POST['remarks'];

                $stmt = $conn->prepare("INSERT INTO records (record_title, record_type, holder_name, identification_number, issue_date, record_status, quantity, unit, submitted_by, remarks) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('ssssssssis', $record_title, $record_type, $holder_name, $identification_number, $issue_date, $record_status, $quantity, $unit, $submitted_by, $remarks);

                if ($stmt->execute()) {
                    echo "<p>Record added successfully!</p>";  // Display success message
                } else {
                    echo "Error: " . $stmt->error;
                }

                $stmt->close();
                $conn->close();
            }
            ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
