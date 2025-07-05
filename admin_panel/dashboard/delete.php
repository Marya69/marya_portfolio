<?php
session_start();
include '../config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to delete users.";
    header("Location: ../index.php"); // Redirect to login page if not logged in
    exit();
}

// Check if 'id' is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Invalid user ID.";
    header("Location: ../dashboard.php");
    exit();
}

$user_id = intval($_GET['id']); // Ensure it's an integer

// Check if the user exists
$stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    $_SESSION['error'] = "User does not exist.";
    header("Location: ../dashboard.php");
    exit();
}
$stmt->close();

// Prevent the logged-in user from deleting themselves
if ($user_id == $_SESSION['user_id']) {
    $_SESSION['error'] = "You cannot delete yourself!";
    header("Location: ../dashboard.php");
    exit();
}

// Proceed with deletion
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "User deleted successfully!";
} else {
    $_SESSION['error'] = "Failed to delete user. Try again!";
}

$stmt->close();
$conn->close();

// Redirect back to the dashboard
header("Location: ../dashboard.php");
exit();
?>