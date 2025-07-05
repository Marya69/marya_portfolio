<?php
session_start();
include '../config.php';

// Prevent accidental output
header('Content-Type: application/json'); // Make sure response is JSON
ob_clean(); // Clear any accidental output
error_reporting(0); // Suppress warnings that could corrupt JSON

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Unauthorized access"]);
    exit();
}

// Handle the GET request for fetching user data (AJAX request)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $skills_id = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT id, skill, about_skill FROM skills WHERE id = ?");
    $stmt->bind_param("i", $skills_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($skill = $result->fetch_assoc()) {
        echo json_encode($skill);
    } else {
        echo json_encode(["error" => "Skill not found"]);
    }
    
    $stmt->close();
    exit();
}
// Ensure correct database connection

// Handle the POST request for updating user data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    if (empty($_POST['skill_id']) || empty($_POST['skill2']) || empty($_POST['about_skill'])) {
       
        $_SESSION['error'] = "All fields are required!";
        header("Location: ../dashboard.php?added=skills");
        exit();
    }

    $skill_id = intval($_POST['skill_id']);
    $skill = trim($_POST['skill2']);
    $about_skill = trim($_POST['about_skill']);

    // Ensure database connection is working
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Fetch existing image before updating
    $stmt = $conn->prepare("SELECT image FROM skills WHERE id = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("i", $skill_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $_SESSION['error'] = "Skill not found!";
        header("Location: ../dashboard.php?added=skills");
        exit();
    }

    $user = $result->fetch_assoc();
    $stmt->close();

    $new_image = $user['image']; // Default to current image

    // Check if a new image is uploaded
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image'];
        $target_dir = "uploads/";
        $image_name = time() . '_' . basename($image['name']);
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($image['tmp_name'], $target_file)) {
                $new_image = $image_name;
            } else {
                $_SESSION['error'] = "Failed to upload image!";
                header("Location: ../dashboard.php?added=skills");
                exit();
            }
        } else {
            $_SESSION['error'] = "Invalid image format! Only JPG, JPEG, PNG, GIF allowed.";
            header("Location: ../dashboard.php?added=skills");
            exit();
        }
    }

    // Update the user data
    $stmt = $conn->prepare("UPDATE skills SET skill = ?, about_skill = ?, image = ? WHERE id = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("sssi", $skill, $about_skill, $new_image, $skill_id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $_SESSION['success'] = "Skill updated successfully!";
        } else {
            $_SESSION['error'] = "No changes made, check input values!";
        }
    } else {
        $_SESSION['error'] = "Failed to update skill! Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    header("Location: ../dashboard.php?added=skills");
    exit();
}

// If the request is not valid
$_SESSION['error'] = "Invalid request!";
header("Location: ../dashboard.php?added=skills");
exit();
?>