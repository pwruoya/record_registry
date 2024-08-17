<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once "../db.php";

// Fetch all records
$sql = "SELECT * FROM records";
$result = $conn->query($sql);

// Check for SQL errors
if (!$result) {
    die("Error executing query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Records</title>
    <link rel="stylesheet" href="../css/style.css"> <!-- Link to your stylesheet -->
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar {
            background-color: #007BFF;
            padding: 10px;
        }
        .navbar a {
            color: white;
            margin-right: 15px;
            text-decoration: none;
            font-weight: bold;
        }
        .navbar a.active {
            text-decoration: underline;
        }
        h1 {
            margin-top: 20px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px auto;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .action-buttons {
            display: flex;
            justify-content: space-around;
        }
        .edit-btn, .delete-btn {
            display: inline-block;
            width: 60px;
            text-align: center;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 3px;
            color: white;
            font-weight: bold;
        }
        .edit-btn {
            background-color: #28a745;
        }
        .edit-btn:hover {
            background-color: #218838;
        }
        .delete-btn {
            background-color: #dc3545;
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="home.php">Home</a>
        <a href="add_record.php">Add Record</a>
        <a href="view_records.php" class="active">View Records</a>
        <a href="logout.php">Logout</a>
    </div>

    <h1>All Records</h1>
    
    <div class="container">
        <?php if ($result->num_rows > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Record ID</th>
                        <th>Record Item</th>
                        <th>Record Type</th>
                        <th>Holder Name</th>
                        <th>Identification Number</th>
                        <th>Issue Date</th>
                        <th>Status</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th>Remarks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['record_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['record_title']); ?></td>
                            <td><?php echo htmlspecialchars($row['record_type']); ?></td>
                            <td><?php echo htmlspecialchars($row['holder_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['identification_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['issue_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['record_status']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($row['unit']); ?></td>
                            <td><?php echo htmlspecialchars($row['remarks']); ?></td>
                            <td class="action-buttons">
                                <a href="edit_record.php?id=<?php echo urlencode($row['record_id']); ?>" class="edit-btn">Edit</a>
                                <a href="delete_record.php?id=<?php echo urlencode($row['record_id']); ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center">No records found.</p>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
