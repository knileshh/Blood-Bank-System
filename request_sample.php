<?php
    session_start();
    require_once 'config.php';

    // Check if the user is logged in as a receiver
    if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'receiver') {
        header("Location: login.php");
        exit();
    }

    // Retrieve the necessary parameters from the request
    $sample_id = $_GET['sample_id'];
    $receiver_id = $_SESSION['user_id'];
    $blood_group = $_GET['blood_group'];

    // Retrieve the hospital ID for the requested blood sample
    $stmt = $conn->prepare("SELECT hospital_id FROM blood_samples WHERE id = ?");
    $stmt->bind_param("i", $sample_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $hospital_id = $row['hospital_id'];

    // Insert the blood sample request into the database
    $stmt = $conn->prepare("INSERT INTO blood_requests (receiver_id, blood_sample_id, hospital_id, request_date, status) VALUES (?, ?, ?, NOW(), 'pending')");
    $stmt->bind_param("iii", $receiver_id, $sample_id, $hospital_id);

    if ($stmt->execute()) {
        // Redirect the user back to the available_blood.php page with a success message
        header("Location: available_blood.php?success=1");
    } else {
        // Redirect the user back to the available_blood.php page with an error message
        header("Location: available_blood.php?error=1");
    }
?>