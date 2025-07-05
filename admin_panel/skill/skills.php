<?php

session_start();
if (isset($_SESSION['success'])) {
    echo "<div style='color: green; padding: 10px; border: 1px solid green; margin-bottom: 10px;'>".$_SESSION['success']."</div>";
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    echo "<div style='color: red; padding: 10px; border: 1px solid red; margin-bottom: 10px;'>".$_SESSION['error']."</div>";
    unset($_SESSION['error']);
}


// dashboard.php
include '../config.php';


// Function to sanitize input
function sanitize_input($data)
{
    return htmlspecialchars(strip_tags(trim($data)));
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Sanitize form inputs
    $skill = sanitize_input($_POST['skill']);
    $describe = sanitize_input($_POST['describe']);

    // Handle file upload securely
    $targetDir = "uploads/"; // Directory where images will be saved
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0775, true); // Create folder if it doesn't exist
    }

    $file = $_FILES['image'];
    $fileName = basename($file["name"]);
    $fileTmpName = $file["tmp_name"];
    $fileSize = $file["size"];
    $fileError = $file["error"];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Allowed file types
    $allowedExt = ["jpg", "jpeg", "png", "gif"];
    $maxFileSize = 2 * 1024 * 1024; // 2MB limit

    if (in_array($fileExt, $allowedExt) && $fileSize <= $maxFileSize && $fileError === 0) {
        // Generate a unique file name
        $newFileName = uniqid("skill_", true) . "." . $fileExt;
        $fileDestination = $targetDir . $newFileName;

        if (move_uploaded_file($fileTmpName, $fileDestination)) {
            // Insert into database using prepared statements
            $stmt = $conn->prepare("INSERT INTO skills (skill, about_skill, image) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $skill, $describe, $newFileName);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Skill added successfully!";
            } else {
                $_SESSION['error'] = "Database error. Please try again.";
            }
            $stmt->close();
        } else {
            $_SESSION['error'] = "Error uploading the image. Please try again.";
        }
    } else {
        $_SESSION['error'] = "Invalid file type or size. Allowed: JPG, PNG, GIF (Max: 2MB)";
    }
    header("Location: ../dashboard.php?added=skills");
    exit();
    
    



}



?>
<style>
.table {
    table-layout: fixed;
    width: 100%;
}

.table td {
    white-space: normal;
    word-wrap: break-word;
}
</style>
<section id="skills" class="p-3">
    <div class="container">
        <h1 style="color: #3498DB;">
            <i class="fa fa-lightbulb p-1"></i> Skills

        </h1>
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mt-4">
            <h3> Skills Table </h3>
            <div class="ms-auto">

                <div class="btn-group">

                    <!-- Add Admin Modal Trigger -->
                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                        data-bs-target="#addAdminModal">
                        <i class="bx bx-list-plus pb-1"></i> Add New Skill
                    </button>
                    <div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addAdminModalLabel">Add New Skill</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="adminForm" method="POST" enctype="multipart/form-data"
                                        action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                        <div class="mb-3">
                                            <label for="skill" class="form-label">Name skill </label>
                                            <input type="text" class="form-control" id="skill" name="skill"
                                                placeholder="Enter Name skill You have" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="describe" class="form-label">Describe Skill</label>
                                            <textarea class="form-control" id="describe" name="describe" rows="4"
                                                placeholder="Enter Describe Skill" required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="image" class="form-label">Upload Image Skill</label>
                                            <input type="file" class="form-control" id="image" name="image"
                                                accept="image/*" required>
                                        </div>


                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" name="submit" class="btn btn-primary">Add New
                                                Skill</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr />

        <div class="container mt-4">
            <table class="table table-bordered table-striped">

                <thead>
                    <tr>
                        <th>Skill</th>
                        <th>Describe</th>
                        <th>Image</th>
                        <th colspan="2">Action</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php
        // Fetch and display skills
        $result = $conn->query("SELECT * FROM skills");
        while ($row = $result->fetch_assoc()):
        ?>
                    <tr>
                        <td><?= $row['skill'] ?></td>
                        <td><?= $row['about_skill'] ?></td>
                        <td><img src="./skill/uploads/<?= $row['image'] ?>" width="50"></td>
                        <td>
                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                onclick="getData2(<?php echo $row['id']; ?>)" style=" color: green;">
                                <i class="fa fa-edit fa-2x"></i>
                            </a>
                        </td>


                        <td>
                            <a href="./skill/delete.php?id=<?php echo $row['id']; ?>" class="delete"
                                data-delete="<?php echo $row['id']; ?>">
                                <i class="fa fa-trash fa-2x" style="color: red;"></i>
                            </a>
                        </td>

                    </tr>
                    <?php endwhile; ?>





                    </tr>

                </tbody>

            </table>
            <!-- update model-->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <!-- Notice the form now wraps the modal content so that the submit button is inside the form -->
                    <form action="./skill/update.php" method="post" id="update_skill" enctype="multipart/form-data">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Update Skill</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="skill_id" name="skill_id">
                                <div class="mb-3">
                                    <label for="skill2" class="form-label">Name skill</label>
                                    <input type="text" class="form-control" id="skill2" name="skill2">
                                </div>
                                <div class="mb-3">
                                    <label for="about_skill" class="form-label">Describe Skill</label>
                                    <textarea class="form-control" id="about_skill" name="about_skill"
                                        rows="4"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="image" class="form-label">Upload Image Skill</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update Skill</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>

</section>