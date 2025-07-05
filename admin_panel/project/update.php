<?php
session_start();
include '../config.php';

// Prevent accidental output
header('Content-Type: application/json'); // Ensure response is JSON
ob_clean(); // Clear any accidental output
error_reporting(0); // Suppress warnings that could corrupt JSON

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Unauthorized access"]);
    exit();
}

// Handle the GET request for fetching user data (AJAX request)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $project_id = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT id, name_project, description FROM projects WHERE id = ?");
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($proj = $result->fetch_assoc()) {
        echo json_encode($proj);
    } else {
        echo json_encode(["error" => "Project not found"]);
    }
    
    $stmt->close();
    exit();
}

function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['project_id']) || empty($_POST['name_proj']) || empty($_POST['description'])) {
        $_SESSION['error'] = "All fields are required!";
        header("Location: ../dashboard.php?added=project");
        exit();
    }

    $project_id = intval($_POST['project_id']);
    $name_proj = sanitize_input($_POST['name_proj']);
    $description = sanitize_input($_POST['description']);

    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Fetch current image and video
    $stmt = $conn->prepare("SELECT image, video FROM projects WHERE id = ?");
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error'] = "Project not found!";
        header("Location: ../dashboard.php?added=project");
        exit();
    }

    $project = $result->fetch_assoc();
    $stmt->close();

    $new_image = $project['image'];
    $new_video = $project['video'];

    $targetDir = "./uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0775, true);
    }

    // Image upload
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image'];
        $imageExt = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
        $allowedImageExt = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageExt, $allowedImageExt)) {
            $new_image_name = uniqid("updated_img_", true) . "." . $imageExt;
            if (move_uploaded_file($image['tmp_name'], $targetDir . $new_image_name)) {
                $new_image = $new_image_name;
            } else {
                $_SESSION['error'] = "Failed to upload new image!";
                header("Location: ../dashboard.php?added=project");
                exit();
            }
        } else {
            $_SESSION['error'] = "Invalid image format!";
            header("Location: ../dashboard.php?added=project");
            exit();
        }
    }

    // Video upload
  // Video upload
if (!empty($_FILES['video']['name'])) {
    $video = $_FILES['video'];
    $videoExt = strtolower(pathinfo($video['name'], PATHINFO_EXTENSION));
    $allowedVideoExt = ['mp4', 'avi', 'mov', 'wmv', 'webm'];
    $maxVideoSize = 100 * 1024 * 1024; // 100MB

    if (!in_array($videoExt, $allowedVideoExt)) {
        $_SESSION['error'] = "Invalid video format!";
        header("Location: ../dashboard.php?added=project");
        exit();
    } elseif ($video['error'] === UPLOAD_ERR_INI_SIZE) {
        $_SESSION['error'] = "Video is too large! Max size is 100MB.";
        header("Location: ../dashboard.php?added=project");
        exit();
    } elseif ($video['size'] > $maxVideoSize) {
        $_SESSION['error'] = "Video is too large! Max size is 100MB.";
        header("Location: ../dashboard.php?added=project");
        exit();
    } elseif ($video['error'] === 0) {
        $new_video_name = uniqid("updated_vid_", true) . "." . $videoExt;
        if (move_uploaded_file($video['tmp_name'], $targetDir . $new_video_name)) {
            $new_video = $new_video_name;
        } else {
            $_SESSION['error'] = "Failed to upload new video!";
            header("Location: ../dashboard.php?added=project");
            exit();
        }
    } else {
        $_SESSION['error'] = "An unexpected error occurred during video upload.";
        header("Location: ../dashboard.php?added=project");
        exit();
    }


    }

    // Update the project
    $stmt = $conn->prepare("UPDATE projects SET name_project = ?, description = ?, image = ?, video = ? WHERE id = ?");
    if (!$stmt) {
        $_SESSION['error'] = "Database error: " . $conn->error;
        header("Location: ../dashboard.php?added=project");
        exit();
    }

    $stmt->bind_param("ssssi", $name_proj, $description, $new_image, $new_video, $project_id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $_SESSION['success'] = "Project updated successfully!";
        } else {
            $_SESSION['error'] = "No changes made. Try modifying the content.";
        }
    } else {
        $_SESSION['error'] = "Failed to update project! Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: ../dashboard.php?added=project");
    exit();
} else {
    $_SESSION['error'] = "Invalid request method!";
    header("Location: ../dashboard.php?added=project");
    exit();
}
?>