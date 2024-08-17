<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once "../db.php";

// Check if 'id' is present in the URL
if (isset($_GET['id'])) {
    $record_id = intval($_GET['id']);
} else {
    echo "<p>Invalid request. Please provide a valid record ID.</p>";
    exit();
}

// Fetch the record details
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $sql = "SELECT * FROM records WHERE record_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $record_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $record = $result->fetch_assoc();

    if (!$record) {
        echo "<p>Record not found.</p>";
        exit();
    }
}

// Update the record
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

    $update_sql = "UPDATE records SET record_title = ?, record_type = ?, holder_name = ?, identification_number = ?, issue_date = ?, record_status = ?, quantity = ?, unit = ?, remarks = ? WHERE record_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssssssssi", $record_title, $record_type, $holder_name, $identification_number, $issue_date, $record_status, $quantity, $unit, $remarks, $record_id);

    if ($update_stmt->execute()) {
        // Redirect to view_records.php after a successful update
        echo "<script>
            alert('Record has been successfully updated.');
            window.location.href = 'view_records.php';
        </script>";
        exit();
    } else {
        echo "<p>Error updating record: " . $update_stmt->error . "</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="navbar">
        <a href="home.php">Home</a>
        <a href="add_record.php">Add Record</a>
        <a href="view_records.php" class="active">View Records</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>

    <h1>Edit Record</h1>
    <form id="editForm" action="edit_record.php?id=<?php echo urlencode($record_id); ?>" method="post" onsubmit="return confirmUpdate()">
        <label for="record_title">Record Title:</label>
        <input type="text" id="record_title" name="record_title" value="<?php echo htmlspecialchars($record['record_title']); ?>" required>
        
        <label for="record_type">Record Type:</label>
        <select id="record_type" name="record_type" required>
            <option value="Personal" <?php if ($record['record_type'] == 'Personal') echo 'selected'; ?>>Personal</option>
            <option value="Property" <?php if ($record['record_type'] == 'Property') echo 'selected'; ?>>Property</option>
            <option value="Legal" <?php if ($record['record_type'] == 'Legal') echo 'selected'; ?>>Legal</option>
            <option value="Other" <?php if ($record['record_type'] == 'Other') echo 'selected'; ?>>Other</option>
        </select>
        
        <label for="holder_name">Holder Name:</label>
        <input type="text" id="holder_name" name="holder_name" value="<?php echo htmlspecialchars($record['holder_name']); ?>" required>
        
        <label for="identification_number">Identification Number:</label>
        <input type="text" id="identification_number" name="identification_number" value="<?php echo htmlspecialchars($record['identification_number']); ?>" required>
        
        <label for="issue_date">Issue Date:</label>
        <input type="date" id="issue_date" name="issue_date" value="<?php echo htmlspecialchars($record['issue_date']); ?>" required>
        
        <label for="record_status">Record Status:</label>
        <select id="record_status" name="record_status">
            <option value="Active" <?php if ($record['record_status'] == 'Active') echo 'selected'; ?>>Active</option>
            <option value="Inactive" <?php if ($record['record_status'] == 'Inactive') echo 'selected'; ?>>Inactive</option>
            <option value="Expired" <?php if ($record['record_status'] == 'Expired') echo 'selected'; ?>>Expired</option>
        </select>
        
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" step="any" value="<?php echo htmlspecialchars($record['quantity']); ?>">
        
        <label for="unit">Unit:</label>
        <input type="text" id="unit" name="unit" value="<?php echo htmlspecialchars($record['unit']); ?>">
        
        <label for="remarks">Remarks:</label>
        <textarea id="remarks" name="remarks"><?php echo htmlspecialchars($record['remarks']); ?></textarea>
        
        <button type="submit" style="width: 100%; padding: 10px; background-color: #007BFF; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">Update Record</button>
    </form>

    <script>
        function confirmUpdate() {
            return confirm("Are you sure you want to update this record?");
        }
    </script>
</body>
</html>
