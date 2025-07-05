<?php
session_start();
include '../config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to delete skills.";
    header("Location: ../index.php"); // Redirect to login page if not logged in
    exit();
}

// Check if 'id' is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Invalid skill ID.";
    header("Location: ../dashboard.php?added=skills");
    exit();
}

$skill_id = intval($_GET['id']); // Ensure it's an integer

// Check if the skill exists
$stmt = $conn->prepare("SELECT id FROM skills WHERE id = ?");
$stmt->bind_param("i", $skill_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    $_SESSION['error'] = "Skill does not exist.";
    header("Location: ../dashboard.php?added=skills");
    exit();
}
$stmt->close();

// Proceed with deletion
$stmt = $conn->prepare("DELETE FROM skills WHERE id = ?");
$stmt->bind_param("i", $skill_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Skill deleted successfully!";
} else {
    $_SESSION['error'] = "Failed to delete skill. Try again!";
}

$stmt->close();
$conn->close();

// Redirect back to the dashboard
header("Location: ../dashboard.php?added=skills");
exit();
?>