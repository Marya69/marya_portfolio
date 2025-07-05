<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to update users.";
    header("Location: ../index.php");
    exit();
}
include '../auth_check.php';
include '../config.php';

if (isset($_SESSION['success'])) {
    echo "<div style='color: green; padding: 10px; border: 1px solid green; margin-bottom: 10px;'>".$_SESSION['success']."</div>";
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    echo "<div style='color: red; padding: 10px; border: 1px solid red; margin-bottom: 10px;'>".$_SESSION['error']."</div>";
    unset($_SESSION['error']);
}

// Count admins
$result = $conn->query("SELECT COUNT(*) AS total_users FROM users");
$row = $result->fetch_assoc();
$total_users = $row['total_users'];

// Add new admin
if (isset($_POST['submit'])) {
    $gmail_u = trim($_POST['gmail_u']);
    $password = trim($_POST['password']);

    if (!empty($gmail_u) && !empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE gmail_u = ?");
        $checkStmt->bind_param("s", $gmail_u);
        $checkStmt->execute();
        $checkStmt->bind_result($userExists);
        $checkStmt->fetch();
        $checkStmt->close();

        if ($userExists > 0) {
            $_SESSION['error'] = "Admin already exists!";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (gmail_u, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $gmail_u, $hashedPassword);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Admin added successfully!";
            } else {
                $_SESSION['error'] = "Error adding admin: " . $stmt->error;
            }
            $stmt->close();
        }
    } else {
        $_SESSION['error'] = "Please fill in all fields!";
    }

    header("Location: ../dashboard.php");
    exit();
}

// Fetch users
$result = $conn->query("SELECT gmail_u, id FROM users");
$users = $result->fetch_all(MYSQLI_ASSOC);

// Fetch experience
$experience_result = $conn->query("SELECT `id`, `date_start_work`, `date_last_work`, `title_of_work`, `name_place_work`, `experiences`, `tools`, `type_job` FROM `experience`");

if ($experience_result && $experience_result->num_rows > 0) {
    $experience_count = $experience_result->num_rows;
} else {
    $experience_count = 0;
}
$project_result = $conn->query("SELECT id FROM projects");

if ($project_result && $project_result->num_rows > 0) {
    $project_count = $project_result->num_rows;
} else {
    $project_count = 0;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <!-- Include your CSS and JS libraries (e.g., Bootstrap, jQuery, FontAwesome) -->
    <style>
    .dashboard-container {
        display: flex;
        flex-wrap: wrap;
        gap: 40px;
        justify-content: start;
        width: 100%;
        max-width: 1200px;
    }

    .dashboard-card {
        flex: 1 1 1 1;
        /* Grow and shrink with a min width */
        max-width: 350px;
        height: 230px;
        /* Fixed height for uniformity */
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(15px);
        border-radius: 15px;
        padding: 60px;
        text-align: center;
        color: #000;
        transition: all 0.3s ease-in-out;
        border: 2px solid #3498DB;
        box-shadow: 0 0 15px rgba(52, 152, 219, 0.2);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: start;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0 20px rgba(52, 152, 219, 0.6);
        border-color: rgba(52, 152, 219, 0.5);
    }

    .icon-container {
        width: 65px;
        height: 65px;

        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 2rem;
        margin: 0 auto 12px;
        transition: all 0.3s ease-in-out;
        box-shadow: 0 0 10px #3498DB;
    }

    .dashboard-card:hover .icon-container {
        transform: scale(1.2);
        box-shadow: 0 0 20px #3498DB;
    }
    </style>
</head>

<body>
    <section id="edit_user" class="p-3">
        <div class="container">
            <h1 style="color: #3498DB;">
                <i class="fa fa-dashboard p-1"></i> Dashboard
            </h1>

            <div class="dashboard-container mt-4">
                <div class=" dashboard-card ">
                    <div class="icon-container users">
                        <i class="fas fa-users" style="color: #3498DB;"></i>
                    </div>
                    <h5>Admin Number</h5>
                    <h3><?php echo $total_users; ?></h3>
                </div>
                <div class="  dashboard-card">
                    <div class="icon-container revenue">
                        <i class="fas fa-graduation-cap" style="color: #3498DB;"></i>
                    </div>
                    <h5>Experiences</h5>
                    <h3><?php echo $experience_count; ?></h3>
                </div>
                <div class="dashboard-card">
                    <div class="icon-container orders">
                        <i class="fas fa-tasks" style="color: #3498DB;"></i>
                    </div>
                    <h5>Projects</h5>
                    <h3><?php echo $project_count; ?></h3>
                </div>
                <div class="dashboard-card">
                    <div class="icon-container visits">
                        <i class="fas fa-chart-line" style="color: #3498DB;"></i>
                    </div>
                    <h5>Visits</h5>
                    <h3>0</h3>
                </div>
            </div>

            <div class="page-wrapper mt-5">
                <div class="page-content">

                    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                        <h3> Admin Table </h3>
                        <div class="ms-auto">

                            <div class="btn-group">

                                <!-- Add Admin Modal Trigger -->
                                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                    data-bs-target="#addAdminModal">
                                    <i class="bx bx-list-plus pb-1"></i> Add New Admin
                                </button>
                                <div class="modal fade" id="addAdminModal" tabindex="-1"
                                    aria-labelledby="addAdminModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="addAdminModalLabel">Add New Admin</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="adminForm" method="POST"
                                                    action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                                    <div class="mb-3">
                                                        <label for="gmail_u" class="form-label">Admin Gmail</label>
                                                        <input type="email" class="form-control" id="gmail_u"
                                                            name="gmail_u" placeholder="Enter admin Gmail" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="password" class="form-label">Password</label>
                                                        <input type="password" class="form-control" id="password"
                                                            name="password" placeholder="Enter Password" required>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" name="submit" class="btn btn-primary">Add
                                                            Admin</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Add Admin Modal -->
                            </div>
                        </div>
                    </div>

                    <hr />

                    <div class="container mt-4">
                        <table class="table table-bordered table-striped">

                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th colspan="2">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['gmail_u']); ?></td>
                                    <td>
                                        <a href="javascript:void(0)" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal"
                                            onclick="getUserData(<?php echo $user['id']; ?>)" style="color: green;">
                                            <i class="fa fa-edit fa-2x"></i>
                                        </a>
                                    </td>

                                    <td>
                                        <a href="./dashboard/delete.php?id=<?php echo $user['id']; ?>" class="delete"
                                            data-delete="<?php echo $user['id']; ?>">
                                            <i class="fa fa-trash fa-2x" style="color: red;"></i>
                                        </a>
                                    </td>



                                </tr>
                                <?php endforeach; ?>
                            </tbody>

                        </table>


                    </div>
                </div>
            </div>
        </div>
        <!-- Update Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="./dashboard/update.php" method="post" id="updateForm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Update Admin</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="userId" name="userId">
                            <div class="form-group mb-3">
                                <label for="gmail_u">Gmail</label>
                                <input type="email" class="form-control" id="gmail_u_modal" name="gmail_u">
                            </div>
                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password_modal" name="password"
                                    placeholder="Your new Password">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Admin</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="container mt-5">
            <h3>Update CV</h3>
            <hr />
            <form action="./dashboard/upload_cv.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="formFile" class="form-label">Update Your CV</label>
                    <input class="form-control" type="file" id="formFile" name="cv" accept=".pdf" required>
                </div>
                <button type="submit" class="btn btn-primary">Upload CV</button>
            </form>
        </div>

        </div>
        </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>