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
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4>Login</h4>
            </div>
            <div class="card-body">
                <?php
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
                            session_start();
                            $_SESSION['user_id'] = $row['id'];
                            $_SESSION['user_type'] = $row['user_type'];

                            // Redirect to the appropriate page based on user type
                            if ($row['user_type'] === 'hospital') {
                                header("Location: add_blood_info.php");
                            } else {
                                header("Location: available_blood.php");
                            }
                            exit();
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

                <?php if (isset($error_message)): ?>
                    <p class="text-danger"><?php echo $error_message; ?></p>
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
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>