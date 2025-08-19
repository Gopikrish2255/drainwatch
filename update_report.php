<?php
// update_report.php
session_start();
include 'db_config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rid     = intval($_POST['report_id']);
    $status  = trim($_POST['status']);
    $remarks = trim($_POST['remarks']);
    $aid     = $_SESSION['admin_id'];

    // Allowed statuses
    $valid_statuses = ['Pending', 'In Progress', 'Resolved'];
    if (!in_array($status, $valid_statuses)) {
        die("❌ Invalid status value.");
    }

    // Update report status
    $stmt1 = $conn->prepare("UPDATE reports SET status=? WHERE report_id=?");
    $stmt1->bind_param("si", $status, $rid);
    $stmt1->execute();

    // Insert into status_updates history
    $stmt2 = $conn->prepare("INSERT INTO status_updates (report_id, admin_id, status, remarks) VALUES (?, ?, ?, ?)");
    $stmt2->bind_param("iiss", $rid, $aid, $status, $remarks);
    $stmt2->execute();

    header("Location: admin_dashboard.php?msg=updated");
    exit;
}
?>
