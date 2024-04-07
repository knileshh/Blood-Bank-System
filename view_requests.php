<?php
require_once 'config.php';

// Error handling (optional, but recommended for debugging)
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start(); // Start the session

// Authentication check
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'hospital') {
    header("Location: login.php");
    exit();
}

$hospital_id = $_SESSION['user_id'];

// Safeguard against potential SQL injection (using prepared statements)
$stmt = $conn->prepare("SELECT u.name AS receiver_name, br.blood_group, br.request_date
                       FROM blood_requests br
                       JOIN users u ON br.receiver_id = u.id
                       WHERE br.hospital_id = ?
                       ORDER BY br.request_date DESC");
$stmt->bind_param("i", $hospital_id); // Bind the hospital ID parameter
$stmt->execute();
$result = $stmt->get_result(); // Get the result set from the prepared statement
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Requests</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4>View Requests</h4>
            </div>
            <div class="card-body">
                <?php if ($result->num_rows > 0): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Receiver Name</th>
                                <th>Blood Group</th>
                                <th>Request Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['receiver_name']; ?></td>
                                <td><?php echo $row['blood_group']; ?></td>
                                <td><?php echo $row['request_date']; ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-info">No requests found for your hospital at this time.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php
    // Close connections (optional, but good practice)
    $stmt->close();
    $conn->close();
    ?>
</body>
</html>