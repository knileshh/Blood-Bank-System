<!DOCTYPE html>
<html>
<head>
    <title>Receiver Registration</title>
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
    <div class="container">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h4>Receiver Registration</h4>
            </div>
            <div class="card-body">
                <?php
                require_once 'config.php';

                // Registration logic for receivers
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $name = $_POST['name'];
                    $email = $_POST['email'];
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $user_type = 'receiver';
                    $blood_group = $_POST['blood_group'];

                    // Insert user data into the database
                    $stmt = $conn->prepare("INSERT INTO users (name, email, password, user_type, blood_group) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssss", $name, $email, $password, $user_type, $blood_group);
                    $stmt->execute();

                    // Redirect or display a success message
                    header("Location: login.php");
                    exit();
                }
                ?>

                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
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
                    <button type="submit" class="btn btn-danger">Register</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>