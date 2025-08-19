<?php include 'db_config.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>View Reports</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
  <h2>All Reported Drains</h2>
  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>Title</th>
        <th>Description</th>
        <th>Location</th>
        <th>District</th>
        <th>Status</th>
        <th>Image</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // Join reports with districts to get district name
      $sql = "SELECT r.*, d.district_name 
              FROM reports r 
              JOIN districts d ON r.district_id = d.district_id 
              ORDER BY r.created_at DESC";
      $res = $conn->query($sql);

      if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
          echo "<tr>";
          echo "<td>{$row['title']}</td>";
          echo "<td>{$row['description']}</td>";
          echo "<td>{$row['location']}</td>";
          echo "<td>{$row['district_name']}</td>";
          
          // Color-coded status
          $statusClass = match($row['status']) {
            "resolved" => "badge bg-success",
            "in-progress" => "badge bg-warning text-dark",
            default => "badge bg-secondary"
          };
          echo "<td><span class='$statusClass'>{$row['status']}</span></td>";

          // Image preview
          if (!empty($row['image_path']) && file_exists($row['image_path'])) {
            echo "<td><img src='{$row['image_path']}' width='100' class='img-thumbnail'></td>";
          } else {
            echo "<td><em>No image</em></td>";
          }

          echo "</tr>";
        }
      } else {
        echo "<tr><td colspan='6' class='text-center'>No reports submitted yet.</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>
</body>
</html>
