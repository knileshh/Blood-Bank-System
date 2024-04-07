<?php
session_start();

require_once 'config.php';

// Authentication check
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'hospital') {
    header("Location: login.php");
    exit();
}

// Add blood sample logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hospital_id = $_SESSION['user_id'];
    $blood_group = $_POST['blood_group'];
    $quantity = $_POST['quantity'];

    // Insert blood sample data into the database
    $stmt = $conn->prepare("INSERT INTO blood_samples (hospital_id, blood_group, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $hospital_id, $blood_group, $quantity);
    $stmt->execute();

    // Redirect or display a success message
    echo "<p class='text-success'>Blood sample added successfully.</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Blood Information</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 500px;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="available_blood.php">Blood Bank System</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="add_blood_info.php">Add Blood Info</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="available_blood.php">Available Blood Samples</a>
                    </li>
                      <li class="nav-item">
                        <a class="nav-link" href="view_requests.php">View Requests</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                   
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4>Add Blood Information</h4>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'hospital'): ?>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <div class="form-group">
                            <label for="blood_group">Blood Group:</label>
                            <select class="form-control" id="blood_group" name="blood_group" required>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="quantity">Quantity:</label>
                            <input type="text" class="form-control" id="quantity" name="quantity" required>
                        </div>
                        <button type="submit" class="btn btn-success">Add Blood Sample</button>
                    </form>
                <?php else: ?>
                    <p>You do not have permission to access this page.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>