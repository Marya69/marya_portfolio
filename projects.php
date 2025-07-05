<?php
header("Content-Type: text/css");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Projects</title>
    <!-- Optional: Link to external Bootstrap CSS if used -->

    <style>
    /* Navigation styles for the Projects Section */
    #myprojects .my-nav {
        padding: 10px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }

    #myprojects .my-nav-link {
        display: inline-block;
        padding: 10px 15px;
        margin: 0 10px;
        font-size: 16px;
        color: #333;
        text-decoration: none;
        position: relative;
        transition: color 0.3s ease;
        cursor: pointer;
    }

    #myprojects .my-nav-link:hover,
    #myprojects .my-nav-link.active {
        color: #007bff;
    }

    #myprojects .my-nav-link.active::after {
        content: "";
        display: block;
        width: 100%;
        height: 3px;
        background: #007bff;
        position: absolute;
        bottom: -5px;
        left: 0;
    }

    /* Projects grid and card styles */
    .projects-grid {
        display: none;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        max-width: 1200px;
        margin: 2rem auto;
    }

    .project-card {
        position: relative;
        border-radius: 10px;
        overflow: hidden;
        transition: transform 0.3s;
    }

    .project-card img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        transition: transform 0.3s;
    }

    .project-info {
        position: absolute;
        bottom: -90%;
        left: 0;
        width: 100%;
        padding: 1.5rem;
        background: rgba(10, 25, 47, 0.8);
        transition: bottom 0.3s;
    }

    /* .project-card:hover img {
        transform: scale(1.1);
    }*/

    .project-card:hover .project-info {
        bottom: 0;
    }

    /* Utility classes for showing/hiding sections */
    .my-content {
        display: none;
    }

    .my-content.active {
        display: grid !important;
    }
    </style>
</head>

<body>
    <section id="myprojects section" class="p-3">
        <!-- Section Header -->
        <div class="div1">

            <h1 class="p-2 border border-1">Check Out What I've Created</h1>
            <p class="p-2">
                Explore a collection of projects where creativity meets code. From sleek, responsive designs to
                intuitive user experiences, each project represents a unique solution tailored to meet client needs.
            </p>
        </div>
        <hr>
        <!-- Navigation for Project Categories -->
        <!-- <nav class="my-nav">
            <a href="#" data-target="all" class="my-nav-link active">All Projects</a>
            <a href="#" data-target="web" class="my-nav-link">Web</a>
        </nav> -->

        <!-- PHP: Fetch projects from the database -->
        <?php
      // Include DB connection configuration.
      include './admin_panel/config.php';

      // Prepare and execute the query.
      $stmt = $conn->prepare("SELECT * FROM projects ORDER BY id DESC");
      $stmt->execute();
      $result = $stmt->get_result();
    ?>

        <!-- Projects Grid Section -->
        <div id="all" class="my-content active projects-grid">
            <?php while ($row = $result->fetch_assoc()): ?>
            <div class="project-card web">
                <img src="admin_panel/project/uploads/<?= htmlspecialchars($row['image']) ?>" alt="Project Image" />
                <div class="project-info">
                    <h3 class="text-white"><?= htmlspecialchars($row['name_project']) ?></h3>
                    <p class="text-white"><?= htmlspecialchars($row['description']) ?></p>
                    <a href="#" class="text-white border border-1 p-1 glow-button" data-bs-toggle="modal"
                        data-bs-target="#videoModal<?= $row['id'] ?>">
                        View Project
                    </a>
                </div>

                <!-- Modal for Video -->
                <div class="modal fade" id="videoModal<?= $row['id'] ?>" tabindex="-1"
                    aria-labelledby="videoModalLabel<?= $row['id'] ?>" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title"><?= htmlspecialchars($row['name_project']) ?></h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-0">
                                <div class="ratio ratio-16x9" style="margin: 0; padding: 0;">

                                    <iframe class="p-0 m-0" srcdoc='
        <video class="m-0"
          src="admin_panel/project/uploads/<?= htmlspecialchars($row["video"]) ?>" 
          muted 
          controls 
          style="width: 100%; height: 100%;"
          autoplay
        ></video>
    
  ' allowfullscreen style="border: none ;"></iframe>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <?php endwhile; ?> <?php $stmt->close(); ?>
        </div>
    </section>


    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const navLinks = document.querySelectorAll(".my-nav-link");
        const sections = document.querySelectorAll(".my-content");

        navLinks.forEach(link => {
            link.addEventListener("click", function(event) {
                event.preventDefault();
                const target = this.getAttribute(
                    "data-target");
                navLinks.forEach(link => link.classList
                    .remove("active"));
                this.classList.add("active");
                sections.forEach(section => section
                    .classList.remove("active"));
                const targetSection = document
                    .getElementById(target);
                if (targetSection) {
                    targetSection.classList.add("active");
                }
            });
        });
    });
    </script>
</body>

</html>