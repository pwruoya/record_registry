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
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="navbar">
        <a href="home.php">Home</a>
        <a href="add_record.php">Add Record</a>
        <a href="view_records.php" class="active">View Records</a>
        <a href="logout.php">Logout</a>
    </div>

    <h1>All Records</h1>
    
    <?php if ($result->num_rows > 0): ?>
        <table>
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
                    <td>
    <a href="edit_record.php?id=<?php echo urlencode($row['record_id']); ?>" class="edit-btn">Edit</a> |
    <a href="delete_record.php?id=<?php echo urlencode($row['record_id']); ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
</td>


                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No records found.</p>
    <?php endif; ?>

    <?php
    $conn->close();
    ?>
</body>
</html>
