<?php
session_start();
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin' LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Direct password check (no hashing)
        if ($password === "Password") {   // <- Hardcoded plain password
          
            $_SESSION['admin_id']   = $row['user_id'];
            $_SESSION['admin_name'] = $row['name'];

            header("Location: admin_dashboard.php");
            exit;
        } else {
            echo "<div class='alert alert-danger text-center mt-3'>❌ Wrong password</div>";
        }
    } else {
        echo "<div class='alert alert-danger text-center mt-3'>❌ Admin not found</div>";
    }

    $stmt->close();
    $conn->close();
}
?>
