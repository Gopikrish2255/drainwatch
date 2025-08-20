<?php
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);
    $location    = trim($_POST['location']);
    $district_id = intval($_POST['district_id']);

    // Handle image upload
    $image_path = NULL;
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = __DIR__ . "/uploads/";   // absolute path
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . "_" . basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $fileName;

        $allowedTypes = ['image/jpeg','image/png','image/gif'];
        if (in_array($_FILES['image']['type'], $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                // store relative path for DB
                $image_path = "uploads/" . $fileName;
            }
        }
    }

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO reports (title, description, location, district_id, image_path) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssds", $title, $description, $location, $district_id, $image_path);

    if ($stmt->execute()) {
        header("Location: success.php?ref=" . urlencode($referenceId));
exit;
 } else {
        echo "❌ Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
