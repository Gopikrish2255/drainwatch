<?php
session_start();
include 'db_config.php';

// Ensure only logged-in users with role "admin" or "authority" can access
if (!isset($_SESSION['admin_id']) && !isset($_SESSION['authority_id'])) {
    header("Location: index.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['report_id'], $_POST['status'])) {
    $reportId = intval($_POST['report_id']);
    $status   = $_POST['status'];
    $remarks  = $_POST['remarks'] ?? '';

    // Determine updater
    $updatedBy = null;
    $role = null;

    if (isset($_SESSION['admin_id'])) {
        $updatedBy = $_SESSION['admin_id'];
        $role = 'admin';
    } elseif (isset($_SESSION['authority_id'])) {
        $updatedBy = $_SESSION['authority_id'];
        $role = 'authority';
    }

    // ✅ Update reports table
    $stmt = $conn->prepare("UPDATE reports SET status = ? WHERE report_id = ?");
    if (!$stmt) {
        die("SQL Error (reports update): " . $conn->error);
    }
    $stmt->bind_param("si", $status, $reportId);
    $stmt->execute();

    // ✅ Insert log into status_updates
    $stmt2 = $conn->prepare("
        INSERT INTO status_updates (report_id, user_id, role, status, remarks) 
        VALUES (?, ?, ?, ?, ?)
    ");
    if (!$stmt2) {
        die("SQL Error (status_updates insert): " . $conn->error);
    }
    $stmt2->bind_param("iisss", $reportId, $updatedBy, $role, $status, $remarks);
    $stmt2->execute();

    // Redirect
    if ($role === 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: authority_dashboard.php");
    }
    exit;
} else {
    echo "❌ Invalid request!";
}
        