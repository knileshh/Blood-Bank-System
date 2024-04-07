<?php
session_start();
require_once 'config.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Available Blood Samples</title>
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
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="available_blood.php">Blood Bank System</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'hospital'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="add_blood_info.php">Add Blood Info</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="available_blood.php">Available Blood Samples</a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <?php
                    // Retrieve available blood samples from the database
                    $stmt = $conn->prepare("SELECT bs.id, bs.blood_group, bs.quantity, u.name AS hospital_name FROM blood_samples bs JOIN users u ON bs.hospital_id = u.id");
                    $stmt->execute();
                    $result = $stmt->get_result();
                ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Blood Group</th>
                            <th>Quantity</th>
                            <th>Hospital</th>
                            <th>Request Sample</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['blood_group']; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td><?php echo $row['hospital_name']; ?></td>
                            <td>
                                <?php
                                    // Check if the user is logged in as a receiver and eligible for the blood group
                                    if (isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'receiver') {
                                        $receiver_id = $_SESSION['user_id'];
                                        $stmt = $conn->prepare("SELECT blood_group FROM users WHERE id = ?");
                                        $stmt->bind_param("i", $receiver_id);
                                        $stmt->execute();
                                        $result_receiver = $stmt->get_result();
                                        $row_receiver = $result_receiver->fetch_assoc();
                                        $receiver_blood_group = $row_receiver['blood_group'];
                                        if ($receiver_blood_group === $row['blood_group']) {
                                            echo "<a href='request_sample.php?sample_id={$row['id']}&blood_group={$row['blood_group']}' class='btn btn-success btn-sm'>Request Sample</a>";
                                        } else {
                                            echo "<span class='text-danger'>Not eligible</span>";
                                        }
                                    } elseif (isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'hospital') {
                                        echo "<span class='text-danger'>Hospitals cannot request samples</span>";
                                    } else {
                                        echo "<a href='login.php' class='btn btn-primary btn-sm'>Log in to Request</a>";
                                    }
                                ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <?php
                    // Display success or error message
                    if (isset($_GET['success']) && $_GET['success'] == 1) {
                        echo "<div class='alert alert-success mt-3'>Blood sample request submitted successfully.</div>";
                    } elseif (isset($_GET['error']) && $_GET['error'] == 1) {
                        echo "<div class='alert alert-danger mt-3'>Error submitting blood sample request.</div>";
                    }
                ?>
            </div>
        </div>
    </div>
</body>
</html>