<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Record</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="navbar">
        <a href="home.php">Home</a>
        <a href="add_record.php" class="active">Add Record</a>
        <a href="view_records.php">View Records</a>
        <a href="logout.php">Logout</a>
    </div>

    <h1>Add New Record</h1>
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
        
        <button type="submit" style="background-color: #007BFF; color: #fff; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer; font-size: 16px;">
            Add Record
        </button>    </form>

    <?php
    session_start();
    if (isset($_SESSION['user_id'])) {
        $submitted_by = $_SESSION['user_id']; // Ensure this matches an ID in the users table

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            require_once "../db.php";

            $record_title = $_POST['record_title'];
            $record_type = $_POST['record_type'];
            $holder_name = $_POST['holder_name'];
            $identification_number = $_POST['identification_number'];
            $issue_date = $_POST['issue_date'];
            $record_status = $_POST['record_status'];
            $quantity = $_POST['quantity'];
            $unit = $_POST['unit'];
            $remarks = $_POST['remarks'];

            $sql = "INSERT INTO records (record_title, record_type, holder_name, identification_number, issue_date, record_status, quantity, unit, submitted_by, remarks)
                    VALUES ('$record_title', '$record_type', '$holder_name', '$identification_number', '$issue_date', '$record_status', '$quantity', '$unit', '$submitted_by', '$remarks')";

if ($conn->query($sql) === TRUE) {
    echo "<p>Record added successfully!</p>";  // Display success message
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

            $conn->close();
        }
    } else {
        echo "User not logged in. Session variable 'user_id' is not set.";
        exit();
    }
    ?>
</body>
</html>
