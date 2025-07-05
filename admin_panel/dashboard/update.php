<?php
session_start();
include '../config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to update users.";
    header("Location: ../index.php");
    exit();
}

// === GET: Fetch user info (AJAX) ===
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $user_id = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT id, gmail_u FROM users WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            echo json_encode($user);
        } else {
            echo json_encode(["error" => "User not found"]);
        }

        $stmt->close();
    } else {
        echo json_encode(["error" => "Failed to prepare statement"]);
    }
    exit();
}

// === POST: Update user info ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['userId']) || empty($_POST['gmail_u'])) {
        $_SESSION['error'] = "Invalid request!";
        header("Location: ../dashboard.php");
        exit();
    }

    $user_id = intval($_POST['userId']);
    $gmail = trim($_POST['gmail_u']);
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Validate Gmail format
    if (!filter_var($gmail, FILTER_VALIDATE_EMAIL) || !str_ends_with($gmail, '@gmail.com')) {
        $_SESSION['error'] = "Invalid Gmail address. Must end with @gmail.com.";
        header("Location: ../dashboard.php");
        exit();
    }

    // Optional: Check for duplicate Gmail (excluding current user)
    $stmt = $conn->prepare("SELECT id,gmail_u FROM users WHERE gmail_u = ? AND id != ?");
    if ($stmt) {
        $stmt->bind_param("si", $gmail, $user_id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $_SESSION['error'] = "This Gmail is already used by another user.";
            $stmt->close();
            header("Location: ../dashboard.php");
            exit();
        }
        $stmt->close();
    }

    // Update user
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET gmail_u = ?, password = ? WHERE id = ?");
        $stmt->bind_param("ssi", $gmail, $hashedPassword, $user_id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET gmail_u = ? WHERE id = ?");
        $stmt->bind_param("si", $gmail, $user_id);
    }

    if ($stmt && $stmt->execute()) {
        $_SESSION['success'] = "User updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update user.";
    }

    if ($stmt) $stmt->close();
    $conn->close();

    header("Location: ../dashboard.php");
    exit();
}
?>