<?php
session_start();

require_once 'config.php';

// Login logic for hospitals and receivers
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Retrieve user data from the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Set session variables or create a token for authentication
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_type'] = $row['user_type'];

            // Redirect to the appropriate page based on user type
            if ($row['user_type'] === 'hospital') {
                header("Location: add_blood_info.php");
                exit();
            } else {
                header("Location: available_blood.php");
                exit();
            }
        } else {
            // Invalid password
            $error_message = "Invalid email or password";
        }
    } else {
        // User not found
        $error_message = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 400px;
            margin-top: 50px;
        }
        .card {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <h1 class="navbar-brand">Blood Bank System</h1>
            <ul class="navbar-nav justify-content-center flex-grow-1">
                <li class="nav-item">
                    <a class="nav-link" href="./available_blood.php">Available Blood</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./add_blood_info.php">Add Blood Info</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./request_sample.php">Request Sample</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./view_requests.php">View Requests</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./register_hospital.php">Register Hospital</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./register_receiver.php">Register Receiver</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <form action="logout.php" method="post" style="display: none;">
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <h2 class="text-center mb-4">Login</h2>
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>