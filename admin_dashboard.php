<?php
session_start();
include 'db_config.php';

// Redirect if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Fetch reports
$res = $conn->query("
    SELECT r.report_id, r.title, r.description, r.location, d.district_name, r.status, r.image_path, r.created_at 
    FROM reports r
    LEFT JOIN districts d ON r.district_id = d.district_id
    ORDER BY r.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - DrainWatch Kerala</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: #f9fafb;
      font-family: 'Poppins', sans-serif;
    }
    /* Navbar */
    .navbar {
      background: linear-gradient(135deg, #2563eb, #1d4ed8);
      box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    }
    .navbar-brand { font-weight: 600; color: white !important; }
    .dashboard-container { padding: 6rem 2rem 4rem; max-width: 1200px; margin: auto; }
    .card { border-radius: 16px; box-shadow: 0 4px 16px rgba(0,0,0,0.05); }

    /* Status badges */
    .status-badge { padding: 0.4rem 0.9rem; border-radius: 10px; font-size: 0.85rem; font-weight: 600; }
    .status-pending { background: #fef3c7; color: #92400e; }
    .status-progress { background: #dbeafe; color: #1e40af; }
    .status-resolved { background: #d1fae5; color: #065f46; }

    /* Table */
    .table img { border-radius: 8px; max-width: 100px; }
    .table th { font-weight: 600; color: #374151; }

    /* Form inside table */
    .dashboard-form select, 
    .dashboard-form textarea {
      font-size: 0.95rem;
      padding: 0.7rem;
      border-radius: 8px;
      border: 1px solid #d1d5db;
    }
    .dashboard-form textarea { resize: vertical; min-height: 50px; }
    .dashboard-form button {
      background: linear-gradient(135deg, #10b981, #059669);
      border: none;
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 8px;
      transition: 0.3s;
    }
    .dashboard-form button:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 20px rgba(16,185,129,0.3);
    }

    /* Footer */
    .footer {
      background: #111827;
      padding: 3rem 2rem 1rem;
      color: white;
      margin-top: 4rem;
    }
    .footer h3 { color: #3b82f6; }
    .footer p, .footer a { color: #9ca3af; }
    .footer a:hover { color: #3b82f6; }
    .footer-bottom {
      text-align: center;
      margin-top: 2rem;
      padding-top: 2rem;
      border-top: 1px solid #374151;
      color: #9ca3af;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
  <div class="container">
    <a class="navbar-brand" href="index.html">
      <i class="fas fa-tint"></i> DrainWatch Kerala - Admin
    </a>
    <div>
      <span class="text-white me-3">👤 <?php echo htmlspecialchars($_SESSION['admin_name']); ?></span>
      <a href="logout.php" class="btn btn-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<!-- Dashboard -->
<div class="dashboard-container">
  <h2 class="mb-4"><i class="fas fa-chart-line"></i> Report Management</h2>
  <div class="card p-4">
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
            <th>Update</th>
          </tr>
        </thead>
        <tbody>
        <?php while ($row = $res->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['title']); ?></td>
            <td><?= htmlspecialchars($row['description']); ?></td>
            <td><?= htmlspecialchars($row['location']); ?></td>
            <td><?= htmlspecialchars($row['district_name']); ?></td>
            <td>
              <span class="status-badge 
                <?php 
                  if ($row['status'] === 'Pending') echo 'status-pending';
                  elseif ($row['status'] === 'In Progress') echo 'status-progress';
                  elseif ($row['status'] === 'Resolved') echo 'status-resolved';
                ?>">
                <?= htmlspecialchars($row['status']); ?>
              </span>
            </td>
            <td>
              <?php if ($row['image_path']): ?>
                <img src="<?= htmlspecialchars($row['image_path']); ?>" alt="Report Image">
              <?php else: ?>
                <em>No image</em>
              <?php endif; ?>
            </td>
            <td><?= date("M d, Y H:i", strtotime($row['created_at'])); ?></td>
            <td>
              <form method="post" action="update_report.php" class="dashboard-form">
                <input type="hidden" name="report_id" value="<?= $row['report_id']; ?>">
                <select name="status" required>
                  <option <?= $row['status']=='Pending'?'selected':'' ?>>Pending</option>
                  <option <?= $row['status']=='In Progress'?'selected':'' ?>>In Progress</option>
                  <option <?= $row['status']=='Resolved'?'selected':'' ?>>Resolved</option>
                </select>
                <textarea name="remarks" placeholder="Remarks..."></textarea>
                <button type="submit">Update</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Footer -->
<footer class="footer">
  <div class="container">
    <h3>DrainWatch Kerala</h3>
    <p>Protecting Kerala's urban areas from flooding through community-driven drainage monitoring.</p>
    <div class="footer-bottom">
      <p>&copy; <?= date("Y") ?> DrainWatch Kerala. Built for flood prevention and community safety.</p>
    </div>
  </div>
</footer>

</body>
</html>
