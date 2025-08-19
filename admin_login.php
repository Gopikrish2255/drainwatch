<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login - DrainWatch Kerala</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <style>
    .btn-back {
      background: linear-gradient(135deg, #2563eb, #1d4ed8);
      color: white;
      font-size: 0.85rem;
      padding: 0.4rem 0.9rem;
      border-radius: 6px;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.3s ease;
    }
    .btn-back:hover {
      background: linear-gradient(135deg, #1d4ed8, #2563eb);
      box-shadow: 0 4px 12px rgba(37,99,235,0.3);
    }
  </style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="container mt-5" style="padding-top:120px; max-width:400px;">

  <div class="card p-4 shadow">
    <h2 class="text-center mb-4">Admin Login</h2>
    <form method="POST" action="admin_auth.php">
      <input type="email" name="email" placeholder="Email" class="form-control mb-3" required>
      <input type="password" name="password" placeholder="Password" class="form-control mb-3" required><br>
      <button type="submit" class="btn btn-admin w-100">Login</button>
    </form>
  </div>
</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>
