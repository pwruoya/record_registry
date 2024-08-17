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

// Confirm deletion via alert box
if ($_SERVER["REQUEST_METHOD"] == "GET" && !isset($_GET['confirm'])) {
    $sql = "SELECT record_title FROM records WHERE record_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $record_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $record = $result->fetch_assoc();

    if (!$record) {
        echo "<p>Record not found.</p>";
        exit();
    }

    // JavaScript to confirm deletion
    echo "<script>
        var confirmed = confirm('Are you sure you want to delete the record titled \"" . htmlspecialchars($record['record_title']) . "\"?');
        if (confirmed) {
            // If confirmed, reload the page with the confirm parameter
            window.location.href = 'delete_record.php?id=" . urlencode($record_id) . "&confirm=true';
        } else {
            // If not confirmed, redirect to view_records.php
            window.location.href = 'view_records.php';
        }
    </script>";
    exit();
}

// Handle actual deletion
if (isset($_GET['confirm']) && $_GET['confirm'] == 'true') {
    $sql = "DELETE FROM records WHERE record_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $record_id);

    if ($stmt->execute()) {
        // Notify the user and redirect to view_records.php
        echo "<script>
            alert('Record has been successfully deleted.');
            window.location.href = 'view_records.php';
        </script>";
    } else {
        echo "<p>Error deleting record: " . $stmt->error . "</p>";
    }
    exit();
}

$conn->close();
?>
