<?php
// update_report.php
session_start();
include 'db_config.php';

// ✅ Check DB connection
if (!$conn) {
    die("❌ Database connection failed: " . mysqli_connect_error());
}

// ✅ Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rid     = intval($_POST['report_id']);
    $status  = trim($_POST['status']);
    $remarks = trim($_POST['remarks']);
    $aid     = $_SESSION['admin_id']; // maps to user_id in DB
    $role    = 'admin'; // assuming this is an admin dashboard

    // ✅ Validate status
    $valid_statuses = ['Pending', 'In Progress', 'Resolved'];
    if (!in_array($status, $valid_statuses)) {
        die("❌ Invalid status value.");
    }

    // ✅ Update status in reports table
    $stmt1 = $conn->prepare("UPDATE reports SET status = ? WHERE report_id = ?");
    if (!$stmt1) {
        die("❌ Prepare failed for UPDATE: " . $conn->error);
    }
    $stmt1->bind_param("si", $status, $rid);
    if (!$stmt1->execute()) {
        die("❌ Execute failed for UPDATE: " . $stmt1->error);
    }
    $stmt1->close();

    // ✅ Insert into status_updates (user_id and role instead of admin_id)
    $stmt2 = $conn->prepare("INSERT INTO status_updates (report_id, user_id, role, status, remarks) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt2) {
        die("❌ Prepare failed for INSERT: " . $conn->error);
    }
    $stmt2->bind_param("iisss", $rid, $aid, $role, $status, $remarks);
    if (!$stmt2->execute()) {
        die("❌ Execute failed for INSERT: " . $stmt2->error);
    }
    $stmt2->close();

    // ✅ Redirect back
    header("Location: admin_dashboard.php?msg=updated");
    exit;
} else {
    die("❌ Invalid request method.");
}
?>
