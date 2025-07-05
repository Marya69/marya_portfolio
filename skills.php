<?php
// Database connection
require_once './admin_panel/config.php'; // Include your database connection

// Fetch skills data from database
$sql = "SELECT skill, about_skill, image FROM skills"; 
$result = $conn->query($sql);
?>
<section id="skills" class="p-3">
    <div class="container p-3">
        <video autoplay muted loop class="w-100">
            <source src="img/0_abstract_background_3840x2160.webm" type="video/mp4">
        </video>
        <h1 class="mt-1 text-center" style="color: #3498DB !important;">Skills & Abilities</h1>

        <div class="row mt-2 row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3 justify-content-center">
            <!-- <div class="col">
                <div class="card bg-light text-center h-100">
                    <div class="image-row pt-2">
                        <img src="./img/html.png" alt="HTML Icon" width="16%">
                        <img src="./img/css.png" alt="CSS Icon" width="20%">
                        <img src="./img/bootstrap.png" alt="Bootstrap Icon" width="20%">
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">HTML & CSS</h5>
                        <p class="card-text flex-grow-1">Expert in HTML5, CSS3, and Bootstrap 5.</p>
                    </div>
                </div>
            </div> -->

            <!-- <div class="col">
                <div class="card bg-light text-center h-100">
                    <div class="image-row pt-2">
                        <img src="./img/php.png" alt="PHP Icon" width="20%">
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">PHP</h5>
                        <p class="card-text flex-grow-1">Building scalable and efficient applications.</p>
                    </div>
                </div>
            </div> -->
            <?php
            if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
            ?>
            <div class="col">
                <div class="card bg-light text-center h-100">
                    <div class="image-row pt-2">
                        <img src="admin_panel/skill/uploads/<?php echo $row['image']; ?>"
                            alt="<?php echo $row['skill']; ?> Icon" width="20%">
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo $row['skill']; ?></h5>
                        <p class="card-text flex-grow-1"><?php echo $row['about_skill']; ?></p>
                    </div>
                </div>
            </div>
            <?php
                }
            } else {
                echo "<p>No skills found</p>";
            }
?>
        </div>
    </div>
    </div>
</section>