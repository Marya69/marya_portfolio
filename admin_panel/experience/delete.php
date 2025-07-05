<?php
session_start();
include '../config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to delete experience.";
    header("Location: ../index.php");
    exit();
}

// Validate ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Invalid experience ID.";
    header("Location: ../dashboard.php?added=experience");
    exit();
}

$experience_id = intval($_GET['id']); // Safe integer conversion

// Check if the experience exists
$stmt = $conn->prepare("SELECT id FROM experience WHERE id = ?");
$stmt->bind_param("i", $experience_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $_SESSION['error'] = "Experience not found.";
    header("Location: ../dashboard.php?added=experience");
    exit();
}
$stmt->close();

// Delete experience
$stmt = $conn->prepare("DELETE FROM experience WHERE id = ?");
$stmt->bind_param("i", $experience_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Experience deleted successfully.";
} else {
    $_SESSION['error'] = "Failed to delete experience. Try again.";
}

$stmt->close();
$conn->close();

header("Location: ../dashboard.php?added=experience");
exit();
?>