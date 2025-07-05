<?php
session_start();

$maxPostSize = 200 * 1024 * 1024;
if ($_SERVER['CONTENT_LENGTH'] > $maxPostSize) {
    $_SESSION['error'] = ["The uploaded file is too large. Max 200MB."];
    header("Location: ../dashboard.php?added=project");
    exit();
}

include '../config.php';

function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $n_projects = isset($_POST['n_project']) ? sanitize_input($_POST['n_project']) : "";
    $description = isset($_POST['describe']) ? sanitize_input($_POST['describe']) : "";

    $_SESSION['error'] = [];

    if (empty($n_projects) || empty($description)) {
        $_SESSION['error'][] = "Project name and description are required.";
    }

    $targetDir = __DIR__ . "/uploads/";
    if (!is_dir($targetDir)) mkdir($targetDir, 0775, true);

    // Image
    $imageNewName = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowedImg = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageExt, $allowedImg)) {
            $_SESSION['error'][] = "Invalid image format.";
        } elseif ($_FILES['image']['size'] > 2 * 1024 * 1024) {
            $_SESSION['error'][] = "Image too large (max 2MB).";
        } else {
            $imageNewName = uniqid("img_", true) . "." . $imageExt;
            move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $imageNewName);
        }
    }

    // Video
    $videoNewName = "";
    if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
        $videoExt = strtolower(pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION));
        $allowedVid = ["mp4", "avi", "mov", "webm"];
        if (!in_array($videoExt, $allowedVid)) {
            $_SESSION['error'][] = "Invalid video format.";
        } elseif ($_FILES['video']['size'] > 100 * 1024 * 1024) {
            $_SESSION['error'][] = "Video too large (max 100MB).";
        } else {
            $videoNewName = uniqid("vid_", true) . "." . $videoExt;
            move_uploaded_file($_FILES['video']['tmp_name'], $targetDir . $videoNewName);
        }
    }

    if (!empty($_SESSION['error'])) {
        header("Location: ../dashboard.php?added=project");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO projects (`name_project`, `description`, `image`, `video`) VALUES (?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssss", $n_projects, $description, $imageNewName, $videoNewName);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Project added successfully!";
        } else {
            $_SESSION['error'][] = "Execution failed: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION['error'][] = "Prepare failed: " . $conn->error;
    }

    header("Location: ../dashboard.php?added=project");
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

<section id="project" class="p-3">

    <div class="container">
        <h1 style="color: #3498DB;">
            <i class="fa fa-code p-1"></i> My Projects

        </h1>
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mt-4">
            <h3> Projects Table </h3>
            <div class="ms-auto">

                <div class="btn-group">

                    <!-- Add Admin Modal Trigger -->
                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                        data-bs-target="#addAdminModal">
                        <i class="bx bx-list-plus pb-1"></i> Add New Project
                    </button>
                    <div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addAdminModalLabel">Add New Project</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="adminForm" method="POST" enctype="multipart/form-data"
                                        action="<?php echo $_SERVER['PHP_SELF']; ?>">

                                        <div class="mb-3">
                                            <label for="n_project" class="form-label">Name Project </label>
                                            <input type="text" class="form-control" id="n_project" name="n_project"
                                                placeholder="Enter Name Project You have" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="describe" class="form-label">Describe Project</label>
                                            <textarea class="form-control" id="describe" name="describe" rows="4"
                                                placeholder="Enter Describe Project" required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="image" class="form-label">Upload Image Project</label>
                                            <input type="file" class="form-control" id="image" name="image"
                                                accept="image/*" required>
                                        </div>


                                        <div class="mb-3">
                                            <label for="video" class="form-label">Upload Video Project</label>
                                            <input type="file" class="form-control" id="video" name="video"
                                                accept="video/*" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" name="submit" class="btn btn-primary">Add New Project
                                            </button>
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
                        <th>Name Project</th>
                        <th>Description</th>
                        <th>Image</th>

                        <th colspan="2">Action</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php
        // Fetch and display skills
        $result = $conn->query("SELECT * FROM projects");
        while ($row = $result->fetch_assoc()):
        ?>
                    <tr>
                        <td><?= $row['name_project'] ?></td>
                        <td><?= $row['description'] ?></td>
                        <td><img src="./project/uploads/<?= $row['image'] ?>" width="50"></td>



                        <td>
                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                onclick="getData3(<?php echo $row['id']; ?>)" style=" color: green;">
                                <i class="fa fa-edit fa-2x"></i>
                            </a>
                        </td>


                        <td>
                            <a href="./project/delete.php?id=<?php echo $row['id']; ?>" class="delete"
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
                    <form action="./project/update.php" method="post" id="update_project" enctype="multipart/form-data">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Update Project</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="project_id" name="project_id">
                                <div class="mb-3">
                                    <label for="name_proj" class="form-label">Name Project</label>
                                    <input type="text" class="form-control" id="name_proj" name="name_proj">
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Describe Project</label>
                                    <textarea class="form-control" id="description" name="description"
                                        rows="4"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="image" class="form-label">Upload Image Project</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                </div>
                                <div class="mb-3">
                                    <label for="video" class="form-label">Upload Video Project</label>
                                    <input type="file" class="form-control" id="video" name="video" accept="video/*">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update Project</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>


</section>