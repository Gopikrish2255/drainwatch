<?php
// DB connection parameters
$host = '127.0.0.1';
$dbname = 'drainwatch_kerala';
$username = 'root';
$password = '';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$report = null;
$statusMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['email'])) {
        $email = trim($_POST['email']);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $sql = "SELECT r.*, d.district_name 
                    FROM reports r 
                    LEFT JOIN districts d ON r.district_id = d.district_id
                    WHERE r.email = ? 
                    ORDER BY r.report_id DESC 
                    LIMIT 1";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                $statusMessage = "Failed to prepare SQL statement: " . htmlspecialchars($conn->error);
            } else {
                $stmt->bind_param('s', $email);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result && $result->num_rows > 0) {
                    $report = $result->fetch_assoc();
                } else {
                    $statusMessage = "No report found for this email.";
                }
                $stmt->close();
            }
        } else {
            $statusMessage = "Please enter a valid email address.";
        }
    } else {
        $statusMessage = "Please enter your email.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Check Drainage Issue Status - DrainWatch Kerala</title>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');
    body {
        font-family: 'Poppins', sans-serif;
        background: #f3f4f6;
        margin: 0; padding: 2rem;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: 100vh;
    }
    .container {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        max-width: 600px;
        width: 100%;
    }
    h1 {
        color: #2563eb;
        margin-bottom: 1.5rem;
        text-align: center;
    }
    label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
        color: #374151;
    }
    input[type="email"] {
        width: 100%;
        padding: 0.7rem;
        font-size: 1rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        margin-bottom: 1rem;
        transition: border-color 0.3s ease;
    }
    input[type="email"]:focus {
        border-color: #2563eb;
        outline: none;
    }
    button {
        width: 100%;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        border: none;
        color: white;
        padding: 0.75rem;
        font-size: 1.1rem;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.3s ease;
        margin-bottom: 1.5rem;
    }
    button:hover {
        background: linear-gradient(135deg, #1d4ed8, #2563eb);
    }
    .status-message {
        font-size: 1.1rem;
        color: #1f2937;
        text-align: center;
        margin-bottom: 1.5rem;
    }
    .report-details {
        background: #e0e7ff;
        padding: 1rem;
        border-radius: 8px;
        color: #1e293b;
    }
    .report-details h2 {
        margin-top: 0;
        color: #3730a3;
    }
    .report-row {
        margin-bottom: 0.8rem;
    }
    .report-label {
        font-weight: 600;
    }
    .report-image {
        max-width: 100%;
        border-radius: 8px;
        margin-top: 1rem;
    }
</style>
</head>
<body>

<div class="container">
    <h1>Check Issue Status</h1>
    <form method="POST" action="">
        <label for="email">Enter Your Email:</label>
        <input type="email" id="email" name="email" placeholder="you@example.com" required />
        <button type="submit">Check Status</button>
    </form>

    <?php if ($statusMessage): ?>
        <div class="status-message"><?php echo $statusMessage; ?></div>
    <?php endif; ?>

    <?php if ($report): ?>
        <div class="report-details">
            <h2>Report Details</h2>
            <div class="report-row"><span class="report-label">Title:</span> <?php echo htmlspecialchars($report['title']); ?></div>
            <div class="report-row"><span class="report-label">Description:</span> <?php echo nl2br(htmlspecialchars($report['description'])); ?></div>
            <div class="report-row"><span class="report-label">Location:</span> <?php echo htmlspecialchars($report['location']); ?></div>
            <div class="report-row"><span class="report-label">District:</span> <?php echo htmlspecialchars($report['district_name'] ?? 'Unknown'); ?></div>
            <div class="report-row"><span class="report-label">Status:</span> <?php echo htmlspecialchars($report['status']); ?></div>
            <div class="report-row"><span class="report-label">Submitted On:</span> <?php echo htmlspecialchars($report['created_at']); ?></div>
            <?php if (!empty($report['image_path']) && file_exists(__DIR__ . '/' . $report['image_path'])): ?>
                <img src="<?php echo htmlspecialchars($report['image_path']); ?>" alt="Report Image" class="report-image" />
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
