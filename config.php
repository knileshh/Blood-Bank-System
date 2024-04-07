<?php
$servername = "localhost";
$username = "id22018907_admin";
$password = "admin@CRA8";
$dbname = "id22018907_blood_bank_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}