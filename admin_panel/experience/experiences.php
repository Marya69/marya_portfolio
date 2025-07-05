<?php
session_start();

// Display success messages
if (isset($_SESSION['success'])) {
    echo "<div style='color: green; padding: 10px; border: 1px solid green; margin-bottom: 10px;'>"
        . htmlspecialchars($_SESSION['success']) . "</div>";
    unset($_SESSION['success']);
}

// Display error messages
if (isset($_SESSION['error'])) {
    if (is_array($_SESSION['error'])) {
        echo "<div style='color: red; padding: 10px; border: 1px solid red; margin-bottom: 10px;'>";
        foreach ($_SESSION['error'] as $error) {
            echo htmlspecialchars($error) . "<br>";
        }
        echo "</div>";
    } else {
        echo "<div style='color: red; padding: 10px; border: 1px solid red; margin-bottom: 10px;'>"
            . htmlspecialchars($_SESSION['error']) . "</div>";
    }
    unset($_SESSION['error']);
}

include '../config.php'; // Your DB connection

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {

    $errors = [];

    // Sanitize input
    $date_start_work = $_POST['date_start_work'] ?? '';
    $date_last_work = $_POST['date_last_work'] ?? '';
    $title_of_work = trim($_POST['title_of_work'] ?? '');
    $name_place_work = trim($_POST['name_place_work'] ?? '');
    $type_job = $_POST['workMode'] ?? '';
    $experiences = $_POST['experiences'] ?? [];
    $tools = $_POST['tools'] ?? [];

    // Basic validation
    if (empty($date_start_work) || empty($date_last_work)) {
        $errors[] = "Both start and end dates are required.";
    } elseif ($date_start_work > $date_last_work) {
        $errors[] = "Start date must be before end date.";
    }

    if (empty($title_of_work)) {
        $errors[] = "Title of work is required.";
    }

    if (empty($name_place_work)) {
        $errors[] = "Place of work is required.";
    }

    if (empty($type_job)) {
        $errors[] = "Work mode is required.";
    }

    if (empty($experiences)) {
        $errors[] = "At least one experience is required.";
    }

    if (empty($tools)) {
        $errors[] = "At least one tool is required.";
    }

    // If there are errors, return them
    if (!empty($errors)) {
        $_SESSION['error'] = $errors;
        header("Location: ../dashboard.php?added=experience");
        exit();
    }

    // Encode arrays to JSON
    $experiences_json = json_encode($experiences, JSON_UNESCAPED_UNICODE);
    $tools_json = json_encode($tools, JSON_UNESCAPED_UNICODE);

    // Insert to DB
    $sql = "INSERT INTO experience (date_start_work, date_last_work, title_of_work, name_place_work, experiences, tools, type_job) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $_SESSION['error'] = "Database error: " . $conn->error;
        header("Location: ../dashboard.php?added=experience");
        exit();
    }

    $stmt->bind_param("sssssss", $date_start_work, $date_last_work, $title_of_work, $name_place_work, $experiences_json, $tools_json, $type_job);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Experience added successfully.";
    } else {
        $_SESSION['error'] = "Failed to add experience: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: ../dashboard.php?added=experience");
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
<section id="experience" class="p-3">

    <div class="container">
        <h1 style="color: #3498DB;">
            <i class="	fa fa-star p-1"></i> Experiences

        </h1>
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mt-4">
            <h3> Experiences Table </h3>
            <div class="ms-auto">

                <div class="btn-group">

                    <!-- Add Admin Modal Trigger -->
                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                        data-bs-target="#addAdminModal">
                        <i class="bx bx-list-plus pb-1"></i> Add New Experience
                    </button>


                </div>
            </div>
        </div>
        <div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addAdminModalLabel">Add New Experience</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="experienceForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <div class="mb-3">
                                <label for="date_start_work" class="form-label">Start Date</label>
                                <input type="date" name="date_start_work" id="date_start_work" class="form-control"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="date_last_work" class="form-label">End Date</label>
                                <input type="date" name="date_last_work" id="date_last_work" class="form-control"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="title_of_work" class="form-label">Title of Work</label>
                                <input type="text" name="title_of_work" id="title_of_work"
                                    placeholder="e.g., Web Developer" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="name_place_work" class="form-label">Place of Work</label>
                                <input type="text" name="name_place_work" id="name_place_work"
                                    placeholder="e.g., Google" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="workMode" class="form-label">Work Mode</label>
                                <select id="workMode" name="workMode" class="form-control" required>
                                    <option value="" disabled selected>Select work mode</option>
                                    <option value="remote">Remote</option>
                                    <option value="office">Office</option>
                                    <option value="freelance">Freelance</option>
                                    <option value="remote+office">Remote + Office</option>
                                </select>
                            </div>

                            <!-- HTML (no changes needed) -->
                            <div class="mb-3">
                                <label for="experienceInput" class="form-label">Experience
                                    Description</label>
                                <div id="experienceContainer1">
                                    <div class="d-flex mb-2">
                                        <input type="text" name="experiences[]" class="form-control me-2" required
                                            placeholder="Add an experience">
                                        <button type="button" class="btn btn-success"
                                            onclick="addExperience(this)">Add</button>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="tools" class="form-label">Tools Used</label>
                                <div id="toolsContainer1">
                                    <div class="d-flex mb-2">
                                        <input type="text" name="tools[]" placeholder="e.g., HTML, CSS, JavaScript"
                                            class="form-control me-2" required>
                                        <button type="button" class="btn btn-success"
                                            onclick="addTool(this)">Add</button>
                                    </div>
                                </div>
                            </div>



                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="submit" class="btn btn-primary">Add New
                                    Experience</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <hr />

        <div class="container mt-4">
            <table class="table table-bordered table-striped">

                <thead>
                    <tr>
                        <th>Date_Start_Work</th>
                        <th>Date_Last_Work</th>
                        <th>Title_Of_Work</th>
                        <th>Name_Place</th>
                        <th>Experiences</th>
                        <th>Tools</th>
                        <th>Work Mode</th>
                        <th colspan="2">Action</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php
        // Fetch and display skills
        $result = $conn->query("SELECT * FROM experience");
        while ($row = $result->fetch_assoc()):
        ?>
                    <tr>
                        <td><?= $row['date_start_work'] ?></td>
                        <td><?= $row['date_last_work'] ?></td>
                        <td><?= $row['title_of_work'] ?></td>
                        <td><?= $row['name_place_work'] ?></td>
                        <td><?= $row['experiences'] ?></td>
                        <td><?= $row['tools'] ?></td>
                        <td><?= $row['type_job'] ?></td>



                        <td>
                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                onclick="getExperienceData(<?php echo $row['id']; ?>)" style=" color: green;">
                                <i class="fa fa-edit fa-2x"></i>
                            </a>
                        </td>


                        <td>
                            <a href="./experience/delete.php?id=<?php echo $row['id']; ?>" class="delete"
                                data-delete="<?php echo $row['id']; ?>">
                                <i class="fa fa-trash fa-2x" style="color: red;"></i>
                            </a>
                        </td>

                    </tr>
                    <?php endwhile; ?>





                    </tr>

                </tbody>

            </table>


        </div>
        <!-- </div> -->
        <!-- update model-->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <!-- Notice the form now wraps the modal content so that the submit button is inside the form -->
                <form action="./experience/update.php" method="post" id="update_experience">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Experience</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <!-- Hidden Experience ID -->
                            <input type="hidden" id="experience_id" name="experience_id">

                            <!-- Start Date -->
                            <div class="mb-3">
                                <label for="date_start_work" class="form-label">Start Date</label>
                                <input type="date" id="date_start_work1" name="date_start_work1" class="form-control"
                                    required>
                            </div>

                            <!-- End Date -->
                            <div class="mb-3">
                                <label for="date_last_work" class="form-label">End Date</label>
                                <!-- End Date -->
                                <input type="date" id="date_last_work1" name="date_last_work1" class="form-control"
                                    required>

                            </div>

                            <!-- Job Title -->
                            <div class="mb-3">
                                <label for="title_of_work" class="form-label">Title of Work</label>
                                <input type="text" id="title_of_work1" name="title_of_work1" class="form-control"
                                    required>

                            </div>

                            <!-- Place of Work -->
                            <div class="mb-3">
                                <label for="name_place_work" class="form-label">Place of Work</label>
                                <!-- Place of Work -->
                                <input type="text" id="name_place_work1" name="name_place_work1" class="form-control"
                                    required>

                            </div>

                            <!-- Work Mode -->
                            <select id="workMode1" name="workMode1" class="form-control" required>
                                <option value="" disabled>Select work mode</option>
                                <option value="remote">Remote</option>
                                <option value="office">Office</option>
                                <option value="freelance">Freelance</option>
                                <option value="remote+office">Remote + Office</option>
                            </select> <br>
                            <div id="experienceContainer">
                            </div>
                            <div id="toolsContainer"> </div>

                        </div>

                        <!-- Submit -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Experience</button>
                        </div>
                    </div>
                </form>


            </div>
        </div>
</section>