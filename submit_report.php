<?php
// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_config.php'; // Make sure this file sets up $conn (mysqli connection)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and assign POST values
    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);
    $location    = trim($_POST['location']);
    $district_id = intval($_POST['district_id']);
    $email       = trim($_POST['email']);
    $image_path  = NULL;

    // Handle image upload if provided
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = __DIR__ . "/uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Generate unique filename to avoid overwriting
        $fileName = time() . "_" . basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $fileName;

        // Allowed mime types
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

        if (in_array($_FILES['image']['type'], $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                // Store relative path in DB
                $image_path = "uploads/" . $fileName;
            } else {
                die("Error uploading the image.");
            }
        } else {
            die("Unsupported image format. Allowed: JPG, PNG, GIF.");
        }
    }

    // Prepare the insert statement (include email)
    $stmt = $conn->prepare("INSERT INTO reports (title, description, location, district_id, email, image_path) VALUES (?, ?, ?, ?, ?, ?)");

    if ($stmt === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }

    // Bind parameters: s=string, i=integer
    if (!$stmt->bind_param("sssiss", $title, $description, $location, $district_id, $email, $image_path)) {
        die("Bind param failed: " . htmlspecialchars($stmt->error));
    }

    // Execute and check for success
    if ($stmt->execute()) {
        // Redirect or show success message
        header("Location: success.php"); // Make sure you have success.php
        exit;
    } else {
        die("Execute failed: " . htmlspecialchars($stmt->error));
    }

    $stmt->close();
    $conn->close();
} else {
    // If accessed without POST
    echo "Invalid request method.";
}
?>
