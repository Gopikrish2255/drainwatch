<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<?php include 'db_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Submit Report</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
  <h2>Report Blocked Drain</h2>
  <form method="POST" action="submit_report.php" enctype="multipart/form-data">
    <div class="mb-3">
      <label>Title</label>
      <input type="text" name="title" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Description</label>
      <textarea name="description" class="form-control" required></textarea>
    </div>
    <div class="mb-3">
      <label>Location</label>
      <input type="text" name="location" class="form-control" required>
    </div>
    <div class="mb-3">
  <label class="form-label">District</label>
  <select name="district_id" class="form-control" required>
    <option value="">-- Select District --</option>
    <?php
    include 'db_config.php';
    $result = $conn->query("SELECT district_id, district_name FROM districts ORDER BY district_name");
    while ($row = $result->fetch_assoc()) {
        echo "<option value='{$row['district_id']}'>{$row['district_name']}</option>";
    }
    ?>
  </select>
</div>

    <div class="mb-3">
      <label>Upload Image (optional)</label>
      <input type="file" name="image" class="form-control">
    </div>
    <button type="submit" class="btn btn-success">Submit Report</button>
  </form>
</div>
</body>
</html>