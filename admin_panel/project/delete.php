<?php
session_start();
include '../config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to delete Project.";
    header("Location: ../index.php"); // Redirect to login page if not logged in
    exit();
}

// Check if 'id' is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Invalid Project ID.";
    header("Location: ../dashboard.php?added=project");
    exit();
}

$skill_id = intval($_GET['id']); // Ensure it's an integer

// Check if the skill exists
$stmt = $conn->prepare("SELECT id FROM projects WHERE id = ?");
$stmt->bind_param("i", $skill_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    $_SESSION['error'] = "Project does not exist.";
    header("Location: ../dashboard.php?added=project");
    exit();
}
$stmt->close();

// Proceed with deletion
$stmt = $conn->prepare("DELETE FROM projects WHERE id = ?");
$stmt->bind_param("i", $skill_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Project deleted successfully!";
} else {
    $_SESSION['error'] = "Failed to delete Project. Try again!";
}

$stmt->close();
$conn->close();

// Redirect back to the dashboard
header("Location: ../dashboard.php?added=project");
exit();
?>