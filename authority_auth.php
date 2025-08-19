<?php
session_start();
include 'db_config.php';

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND role='authority' LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    // Direct comparison (since no hashing)
    if ($password === $user['password']) {
        $_SESSION['authority_id'] = $user['user_id'];
        $_SESSION['authority_name'] = $user['name'];
        header("Location: authority_dashboard.php");
        exit;
    } else {
        echo "❌ Invalid password";
    }
} else {
    echo "❌ No such user";
}
?>
