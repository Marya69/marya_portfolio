<?php

include './admin_panel/config.php';

$sql = "SELECT cv_path FROM cvs ORDER BY uploaded_at DESC LIMIT 1";
$result = $conn->query($sql);
$cvPath = ($result->num_rows > 0) ? $result->fetch_assoc()['cv_path'] : "#";



// SQL query to count all experiences
$sql = "SELECT COUNT(*) AS total_experiences FROM experience";
$result = $conn->query($sql);

$sql = "SELECT COUNT(*) AS total_projects FROM projects";
$result2 = $conn->query($sql);


?>


<section id="about-me" class="p-3 section">
    <style>
    /* Start with your default size for laptop and desktop */
    .responsive-number {
        font-size: 7rem;
    }

    /* Smaller font for tablets */
    @media (max-width: 991.98px) {
        .responsive-number {
            font-size: 5rem;
        }
    }

    /* Smaller font for mobile */
    @media (max-width: 575.98px) {
        .responsive-number {
            font-size: 3rem;
        }
    }
    </style>
    <video autoplay muted loop>
        <source src="img/0_abstract_background_3840x2160.webm" type="video/mp4">
    </video>

    <div class="row ">
        <div class="col-md-6">
            <h1 class="haeding1">HI, I'M MARYA JABAR</h1>
            <h4 class="heading2" style="font-size: 30px;"> web developer</h4>

            <h6 class="heading3">I'm a web developer specializing in back-end and front-end. Explore my <a href=""
                    data-target="projects">
                    project portfolio</a> and <a href="">online resume</a>.</h6>
            <!--buttons  -->
            <div class="d-flex flex-column flex-sm-row gap-3 mt-4 " style="margin-left: 13px;">
                <a href="#" class="btn d-flex align-items-center text-white" data-target="projects"
                    style="background-color: #3498DB !important;">
                    <i class="fas fa-arrow-right me-2"></i> View My Projects
                </a>


                <a href="admin_panel/dashboard/<?= $cvPath ?>" class="btn btn-dark d-flex align-items-center text-white"
                    target="_blank" type="application/pdf" rel="alternate" media="print">
                    <i class="fas fa-file-alt me-2"></i> Quick CV
                </a>
            </div>

            <!-- number of years of experience -->
            <div class="container mt-5 gy-4">
                <div class="row text-center gy-4 ">
                    <!-- Add gy-3 for vertical spacing -->
                    <div class="col-12 col-sm-12 col-md-6 col-lg-4 d-flex align-items-center mb-5 adjustable-div ">
                        <h1 class="m-0" style="font-size: 7rem; color: #3498DB;"><?php   if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo $row["total_experiences"];
    } else {
        echo "0";
    }
?></h1>
                        <hr class="mx-3 my-3 my-md-0"
                            style="width: 1px; height: 90px; background-color: black; border:1px solid black ">
                        <h6 class="m-0">Years of Experience</h6>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-4 d-flex align-items-center mb-5 adjustable-div  ">
                        <h1 class="m-0 " style="font-size: 7rem; color: #3498DB;">
                            <?php if ($result2->num_rows > 0) {
            $row2 = $result2->fetch_assoc();
            echo $row2["total_projects"];
        } else {
            echo "0";
        }

        $conn->close();
        ?></h1>
                        <hr class="mx-3 my-3 my-md-0"
                            style="width: 1px; height: 90px; background-color: black;border:1px solid black ">
                        <h6 class="m-0 ">Projects Completed</h6>
                    </div>
                </div>
            </div>




        </div>


        <div class="col-12 col-md-6 imagaka d-none d-md-block " style="margin-top:9rem;">
            <img src="img/fb_img_1670550693688.jpg" class="img-fluid rounded " width="80%" alt=".....">
        </div>





    </div>

</section>