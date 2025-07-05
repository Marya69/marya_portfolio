<?php
session_start();
include '../config.php';

// Prevent accidental output
header('Content-Type: application/json');
ob_clean();
error_reporting(0); // Hide warnings

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Unauthorized access"]);
    exit();
}

// Handle GET request to retrieve experience data
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $experience_id = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT id, date_start_work, date_last_work, title_of_work, name_place_work, experiences, tools, type_job FROM experience WHERE id = ?");
    $stmt->bind_param("i", $experience_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($exp = $result->fetch_assoc()) {
        $exp['experiences'] = json_decode($exp['experiences'], true) ?? [];
        $exp['tools'] = json_decode($exp['tools'], true) ?? [];
        echo json_encode($exp, JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(["error" => "Experience not found"]);
    }

    $stmt->close();
    exit();
}

// Handle POST request to update experience
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $experience_id = intval($_POST['experience_id'] ?? 0);
    $date_start_work = $_POST['date_start_work1'] ?? '';
    $date_last_work = $_POST['date_last_work1'] ?? '';
    $title_of_work = trim($_POST['title_of_work1'] ?? '');
    $name_place_work = trim($_POST['name_place_work1'] ?? '');
    $work_mode = $_POST['workMode1'] ?? '';
    $experiences = $_POST['experiences'] ?? [];
    $tools = $_POST['tools'] ?? [];

    // Filter out empty values
    $experiences = array_filter(array_map('trim', $experiences));
    $tools = array_filter(array_map('trim', $tools));

    // Validate fields
    if (
        !$experience_id || empty($date_start_work) || empty($date_last_work) ||
        empty($title_of_work) || empty($name_place_work) || empty($work_mode) ||
        empty($experiences) || empty($tools)
    ) {
        $_SESSION['error'] = "Please fill in all required fields.";
        header("Location: ../dashboard.php?added=experience");
        exit();
    }

    // Check if start date is earlier than or equal to end date
    if (strtotime($date_start_work) > strtotime($date_last_work)) {
        $_SESSION['error'] = "Start date cannot be after the end date.";
        header("Location: ../dashboard.php?added=experience");
        exit();
    }

    $experiences_json = json_encode($experiences, JSON_UNESCAPED_UNICODE);
    $tools_json = json_encode($tools, JSON_UNESCAPED_UNICODE);

    $stmt = $conn->prepare("UPDATE experience 
        SET date_start_work = ?, date_last_work = ?, title_of_work = ?, 
            name_place_work = ?, type_job = ?, experiences = ?, tools = ?
        WHERE id = ?");
    $stmt->bind_param(
        "sssssssi",
        $date_start_work,
        $date_last_work,
        $title_of_work,
        $name_place_work,
        $work_mode,
        $experiences_json,
        $tools_json,
        $experience_id
    );

    if ($stmt->execute()) {
        $_SESSION['success'] = "Experience updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update experience. Error: " . $stmt->error;
    }

    $stmt->close();
    header("Location: ../dashboard.php?added=experience");
    exit();
}

// Fallback redirect if request is invalid
header("Location: ../dashboard.php?added=experience");
exit();
?>