<?php
// stats_api.php
header('Content-Type: application/json');
include 'db_config.php';

// Initialize counters
$reports = 0;
$resolved = 0;
$pending = 0;
$districts = 0;

// Total number of reports
$result = $conn->query("SELECT COUNT(*) AS total FROM reports");
if ($result && $row = $result->fetch_assoc()) {
    $reports = intval($row['total']);
}

// Resolved issues
$result = $conn->query("SELECT COUNT(*) AS resolved FROM reports WHERE status = 'Resolved'");
if ($result && $row = $result->fetch_assoc()) {
    $resolved = intval($row['resolved']);
}

// Pending issues (includes both Pending & In Progress)
$result = $conn->query("SELECT COUNT(*) AS pending FROM reports WHERE status IN ('Pending', 'In Progress')");
if ($result && $row = $result->fetch_assoc()) {
    $pending = intval($row['pending']);
}

// Districts
$result = $conn->query("SELECT COUNT(*) AS total FROM districts");
if ($result && $row = $result->fetch_assoc()) {
    $districts = intval($row['total']);
}

// Return JSON
echo json_encode([
    'reports'   => $reports,
    'resolved'  => $resolved,
    'pending'   => $pending,
    'districts' => $districts
]);
