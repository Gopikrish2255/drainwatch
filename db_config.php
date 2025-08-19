<?php
// app/includes/config.php
// Adjust these for your MySQL environment (XAMPP/WAMP)
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'drainwatch_kerala');
define('DB_USER', 'root');
define('DB_PASS', '');

// Base URL (no trailing slash). Example for local XAMPP: http://localhost/drain_watch_kerala/public
define('BASE_URL', '');
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
