<?php
session_start();

// Prevent unauthorized access
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to login if not authenticated
    exit();
}

// Session Timeout (Auto Logout after 30 min)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}
$_SESSION['last_activity'] = time(); // Reset session timeout

// Prevent Session Hijacking (Protects against stolen session cookies)
if (!isset($_SESSION['fingerprint'])) {
    $_SESSION['fingerprint'] = hash('sha256', $_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']);
} elseif ($_SESSION['fingerprint'] !== hash('sha256', $_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inder&family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Open+Sans:wght@300;400&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- <link rel="stylesheet" href="./style.css"> -->
    <link rel="stylesheet" href="../style/style.css">
</head>

<body>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="js copy.js"></script>
    <script>
    function getUserData(userId) {
        fetch(`./dashboard/update.php?id=${userId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('userId').value = data.id;
                document.getElementById('gmail_u_modal').value = data.gmail_u;
                // For security, the password field is left empty.
                document.getElementById('password').value = data.password;
                // The modal is already triggered via data-bs-target so no need to show it again manually.
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading user data');
            });
    }
    </script>
    <script>
    function getData2(skill_id) {
        fetch(`./skill/update.php?id=${skill_id}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    // Populate the form fields
                    document.getElementById('skill_id').value = data.id || '';
                    document.getElementById('skill2').value = data.skill || '';
                    document.getElementById('about_skill').value = data.about_skill || '';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading skill data');
            });
    }
    </script>




    <script>
    function getData3(project_id) {
        fetch(`./project/update.php?id=${project_id}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    // Populate the form fields
                    document.getElementById('project_id').value = data.id || '';
                    document.getElementById('name_proj').value = data.name_project || '';
                    document.getElementById('description').value = data.description || '';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading project data');
            });
    }
    </script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    $(document).ready(function() {
        $(document).on('click', '.delete', function(e) {
            e.preventDefault();
            var link = $(this).attr('href');

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Deleted!",
                        text: "Your file has been deleted.",
                        icon: "success"
                    }).then(() => {
                        // Redirect AFTER the confirmation message
                        window.location.href = link;
                    });
                }
            });
        });
    });
    </script>


    <script>
    function getExperienceData(experience_id) {
        fetch(`./experience/update.php?id=${experience_id}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    // Populate basic fields
                    document.getElementById('experience_id').value = data.id || '';
                    document.getElementById('date_start_work1').value = data.date_start_work || '';
                    document.getElementById('date_last_work1').value = data.date_last_work || '';
                    document.getElementById('title_of_work1').value = data.title_of_work || '';
                    document.getElementById('name_place_work1').value = data.name_place_work || '';
                    document.getElementById('workMode1').value = data.type_job || '';

                    // Populate experiences with label
                    const expContainer = document.getElementById('experienceContainer');
                    expContainer.innerHTML = ''; // Clear old

                    const expLabel = document.createElement('label');
                    expLabel.className = 'form-label';
                    expLabel.textContent = 'Experience Description';
                    expContainer.appendChild(expLabel);

                    (data.experiences || []).forEach(exp => {
                        const div = document.createElement('div');
                        div.className = 'mb-2';
                        div.innerHTML = `
                        <input type="text" name="experiences[]" class="form-control" value="${exp}" required>
                    `;
                        expContainer.appendChild(div);
                    });

                    if (!data.experiences || data.experiences.length === 0) {
                        const div = document.createElement('div');
                        div.className = 'mb-2';
                        div.innerHTML = `
                        <input type="text" name="experiences[]" class="form-control" placeholder="Add an experience" required>
                    `;
                        expContainer.appendChild(div);
                    }

                    // Populate tools with label
                    const toolsContainer = document.getElementById('toolsContainer');
                    toolsContainer.innerHTML = ''; // Clear old

                    const toolsLabel = document.createElement('label');
                    toolsLabel.className = 'form-label';
                    toolsLabel.textContent = 'Tools Used';
                    toolsContainer.appendChild(toolsLabel);

                    (data.tools || []).forEach(tool => {
                        const div = document.createElement('div');
                        div.className = 'mb-2';
                        div.innerHTML = `
                        <input type="text" name="tools[]" class="form-control" value="${tool}" required>
                    `;
                        toolsContainer.appendChild(div);
                    });

                    if (!data.tools || data.tools.length === 0) {
                        const div = document.createElement('div');
                        div.className = 'mb-2';
                        div.innerHTML = `
                        <input type="text" name="tools[]" class="form-control" placeholder="e.g., HTML, CSS, JavaScript" required>
                    `;
                        toolsContainer.appendChild(div);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to load experience data');
            });
    }
    </script>

    <script>
    // Add Experience
    function addExperience(button) {
        const container = document.getElementById("experienceContainer1");

        const newRow = document.createElement("div");
        newRow.className = "d-flex mb-2";

        const input = document.createElement("input");
        input.type = "text";
        input.name = "experiences[]";
        input.placeholder = "Add an experience";
        input.className = "form-control me-2";
        input.required = true;

        const addBtn = document.createElement("button");
        addBtn.type = "button";
        addBtn.className = "btn btn-success me-1";
        addBtn.textContent = "Add";
        addBtn.onclick = function() {
            addExperience(this);
        };

        const delBtn = document.createElement("button");
        delBtn.type = "button";
        delBtn.className = "btn btn-danger";
        delBtn.textContent = "Delete";
        delBtn.onclick = function() {
            removeField(this);
        };

        newRow.appendChild(input);
        newRow.appendChild(addBtn);
        newRow.appendChild(delBtn);

        container.appendChild(newRow);
    }

    // Add Tool
    function addTool(button) {
        const container = document.getElementById("toolsContainer1");

        const newRow = document.createElement("div");
        newRow.className = "d-flex mb-2";

        const input = document.createElement("input");
        input.type = "text";
        input.name = "tools[]";
        input.placeholder = "e.g., HTML, CSS, JavaScript";
        input.className = "form-control me-2";
        input.required = true;

        const addBtn = document.createElement("button");
        addBtn.type = "button";
        addBtn.className = "btn btn-success me-1";
        addBtn.textContent = "Add";
        addBtn.onclick = function() {
            addTool(this);
        };

        const delBtn = document.createElement("button");
        delBtn.type = "button";
        delBtn.className = "btn btn-danger";
        delBtn.textContent = "Delete";
        delBtn.onclick = function() {
            removeField(this);
        };

        newRow.appendChild(input);
        newRow.appendChild(addBtn);
        newRow.appendChild(delBtn);

        container.appendChild(newRow);
    }

    // Remove row
    function removeField(button) {
        button.parentElement.remove();
    }
    </script>

</body>

</html>