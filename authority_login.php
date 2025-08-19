<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Authority Login - DrainWatch Kerala</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="container mt-5" style="padding-top:120px; max-width:400px;">
  <h2 class="text-center mb-4">Authority Login</h2>
  <form method="POST" action="authority_auth.php" class="card p-4 shadow">
    <input type="email" name="email" placeholder="Email" class="form-control mb-3" required>
    <input type="password" name="password" placeholder="Password" class="form-control mb-3" required><br>
    
    <button type="submit" class="btn btn-success w-100">
      <i class="fas fa-sign-in-alt"></i> Login
    </button>
  </form>
</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>
