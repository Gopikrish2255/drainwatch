<?php
session_start();
include 'db_config.php';

// Redirect if not logged in as authority
if (!isset($_SESSION['authority_id'])) {
    header("Location: authority_login.php");
    exit;
}

$aid = $_SESSION['authority_id'];

// ✅ Fetch authority details
$sql = "
    SELECT u.name AS authority_name, u.district_id, d.district_name
    FROM users u
    LEFT JOIN districts d ON u.district_id = d.district_id
    WHERE u.user_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $aid);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();

$authorityName = $user['authority_name'];
$authorityDistrict = $user['district_id'];

// ✅ Selected district from filter (fallback = authority’s own district)
$selectedDistrict = $_GET['district_id'] ?? $authorityDistrict;

// ✅ Fetch the selected district name
$stmt3 = $conn->prepare("SELECT district_name FROM districts WHERE district_id = ?");
$stmt3->bind_param("i", $selectedDistrict);
$stmt3->execute();
$districtRes = $stmt3->get_result();
$selectedDistrictName = $districtRes->fetch_assoc()['district_name'] ?? "Unknown";

// ✅ Fetch all districts for dropdown
$districts = $conn->query("SELECT district_id, district_name FROM districts ORDER BY district_name");

// ✅ Fetch reports for selected district
$stmt2 = $conn->prepare("
    SELECT r.report_id, r.title, r.description, r.location, d.district_name, r.status, r.image_path, r.created_at
    FROM reports r
    LEFT JOIN districts d ON r.district_id = d.district_id
    WHERE r.district_id = ?
    ORDER BY r.created_at DESC
");
$stmt2->bind_param("i", $selectedDistrict);
$stmt2->execute();
$reports = $stmt2->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Authority Dashboard - DrainWatch Kerala</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <style>
    .filter-form {
        margin-bottom: 20px;
        display: flex;
        gap: 10px;
        align-items: center;
    }
    .filter-form select {
        padding: 8px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-family: 'Poppins', sans-serif;
    }
    .filter-form button {
        padding: 8px 16px;
        border: none;
        border-radius: 8px;
        background: #2563eb;
        color: white;
        cursor: pointer;
    }
    .filter-form button:hover {
        background: #1d4ed8;
    }
  </style>
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="container" style="padding-top:120px;">
    <!-- 🔹 Updated to show dynamic district name -->
    <h2>Welcome, <?= htmlspecialchars($authorityName) ?> (<?= htmlspecialchars($selectedDistrictName) ?>)</h2>
    <p class="text-muted">Filter reports by district:</p>

    <!-- 🔹 District Filter -->
    <form method="get" class="filter-form">
        <select name="district_id">
            <?php while($d = $districts->fetch_assoc()): ?>
                <option value="<?= $d['district_id']; ?>" 
                    <?= ($d['district_id'] == $selectedDistrict) ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($d['district_name']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit"><i class="fas fa-filter"></i> Filter</button>
    </form>

    <!-- 🔹 Reports Table -->
    <div class="card p-3">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Location</th>
                        <th>District</th>
                        <th>Status</th>
                        <th>Image</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = $reports->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['title']); ?></td>
                        <td><?= htmlspecialchars($row['description']); ?></td>
                        <td><?= htmlspecialchars($row['location']); ?></td>
                        <td><?= htmlspecialchars($row['district_name']); ?></td>
                        <td><?= htmlspecialchars($row['status']); ?></td>
                        <td>
                          <?php if ($row['image_path']): ?>
                              <img src="<?= $row['image_path']; ?>" alt="Report Image" style="max-width:100px; border-radius:6px;">
                          <?php else: ?>
                              <em>No image</em>
                          <?php endif; ?>
                        </td>
                        <td><?= date("M d, Y H:i", strtotime($row['created_at'])); ?></td>
                        <td>
                          <form method="post" action="update_status.php" class="d-flex gap-1">
                              <input type="hidden" name="report_id" value="<?= $row['report_id']; ?>">
                              <select name="status" class="form-select form-select-sm">
                                <option <?= ($row['status']=='Pending') ? 'selected':''; ?>>Pending</option>
                                <option <?= ($row['status']=='In Progress') ? 'selected':''; ?>>In Progress</option>
                                <option <?= ($row['status']=='Resolved') ? 'selected':''; ?>>Resolved</option>
                              </select>
                              <button type="submit" class="btn btn-primary btn-sm">Update</button>
                          </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
