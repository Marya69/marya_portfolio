<?php
session_start();
include '../config.php';

// Validate presence
if (!isset($_POST['phone'], $_POST['gmail'])) {
    $_SESSION['error'] = "Both fields are required.";
    header("Location: ../dashboard.php?added=contact");
    exit;
}

$phone = trim($_POST['phone']);
$gmail = trim($_POST['gmail']);

// Validate phone: only numbers, exactly 11 digits
if (!preg_match('/^\d{11}$/', $phone)) {
    $_SESSION['error'] = "Phone number must be exactly 11 digits.";
    header("Location: ../dashboard.php?added=contact");
    exit;
}

// Validate gmail
if (!preg_match('/^[a-zA-Z0-9._%+-]+@gmail\.com$/', $gmail)) {
    $_SESSION['error'] = "Please enter a valid Gmail address.";
    header("Location: ../dashboard.php?added=contact");
    exit;
}

// Update the contact
$query = "UPDATE contacts SET phone_n = ?, gmail = ? WHERE id = 1"; // Replace with dynamic ID if needed
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $phone, $gmail);

if ($stmt->execute()) {
    $_SESSION['success'] = "Your contact was updated successfully!";
    header("Location: ../dashboard.php?added=contact");
    exit;
} else {
    $_SESSION['error'] = "Update failed. Please try again.";
    header("Location: ../dashboard.php?added=contact");
    exit;
}