<?php
session_start();
include '../config.php';// Ensure $conn is properly initialized

// Enable error reporting for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$maxFileSize = 5 * 1024 * 1024; // 5MB in bytes

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["cv"])) {
    $uploadDir = "uploads/"; // Directory to store uploaded CVs
    $originalFileName = basename($_FILES["cv"]["name"]); // Keep original filename
    $fileExt = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION)); // Convert extension to lowercase
    $fileSize = $_FILES["cv"]["size"]; // Get file size

    // Ensure it's a valid PDF file
    if ($fileExt !== "pdf") {
        $_SESSION['error'] = "Only PDF files are allowed.";
        header("Location: ../dashboard.php");
        exit;
    }

    // Check file size
    if ($fileSize > $maxFileSize) {
        $_SESSION['error'] = "Your CV is too large. Please upload a file smaller than 5MB.";
        header("Location: ../dashboard.php");
        exit;
    }

    // Generate unique filename
    $fileName = time() . "_" . preg_replace('/\s+/', '_', $originalFileName); // Replace spaces
    $uploadFile = $uploadDir . $fileName;

    // Ensure the uploads directory exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Move uploaded file
    if (move_uploaded_file($_FILES["cv"]["tmp_name"], $uploadFile)) {
        // Save CV path in the database
        $cvPath = $conn->real_escape_string($uploadFile);
        $sql = "INSERT INTO cvs (cv_path) VALUES ('$cvPath')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['success'] = "Your CV was successfully uploaded.";
        } else {
            $_SESSION['error'] = "Database error: " . $conn->error;
            error_log("Database Insert Error: " . $conn->error); // Log error
        }
    } else {
        $_SESSION['error'] = "Failed to upload CV.";
        error_log("File Move Error: " . $_FILES["cv"]["error"]); // Log file error
    }
}

// Redirect after processing
header("Location: ../dashboard.php");
exit;
?>