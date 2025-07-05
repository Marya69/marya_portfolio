<section id="experience section" class="p-3 position-relative">
    <!-- Background video -->
    <video autoplay muted loop class="position-absolute w-100 h-100 object-fit-cover"
        style="z-index: -1; top: 0; left: 0;">
        <source src="img/0_abstract_background_3840x2160.webm" type="video/mp4">
    </video>

    <div class="container">
        <div class="text-center mb-5">
            <h1 class="mt-3" style="color: #3498DB !important;">Experience</h1>

        </div>

        <ul class="timeline">
            <?php
            include './admin_panel/config.php'; // DB connection

            $query = "SELECT date_start_work, date_last_work, title_of_work, name_place_work, experiences, tools, type_job FROM experience ORDER BY date_start_work DESC";
            $result = $conn->query($query);

            $count = 0;
            while ($experience = $result->fetch_assoc()):
                $dateStart = date('M Y', strtotime($experience['date_start_work']));
                $dateEnd = date('M Y', strtotime($experience['date_last_work']));
                $experienceList = json_decode($experience['experiences'], true) ?? [];
                $toolList = json_decode($experience['tools'], true) ?? [];

                // Alternate timeline item class
                $timelineClass = ($count % 2 === 1) ? 'timeline-inverted' : '';
                $count++;
            ?>
            <li class="<?= $timelineClass ?>">
                <div class="timeline-image  rounded-circle"></div>
                <div class="timeline-panel  p-4 rounded">
                    <div class="timeline-heading">
                        <h5 class="text-muted"><?= htmlspecialchars($dateStart) ?> – <?= htmlspecialchars($dateEnd) ?>
                        </h5>
                        <h4 class="subheading fw-semibold">
                            <?= htmlspecialchars($experience['title_of_work'])?>

                            |
                            <?= htmlspecialchars($experience['name_place_work']) ?>
                            (<?= htmlspecialchars($experience['type_job'])?>)</h4>

                    </div>
                    <div class="timeline-body mt-2">
                        <ul class="text-muted ps-3">
                            <?php foreach ($experienceList as $exp): ?>
                            <li><?= htmlspecialchars($exp) ?></li>
                            <?php endforeach; ?>

                            <?php if (!empty($toolList)): ?>
                            <li>Developed and deployed a custom POS system using:
                                <ul class="tech-stack ps-3">
                                    <?php foreach ($toolList as $tool): ?>
                                    <li><?= htmlspecialchars($tool) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </li>
            <?php endwhile; ?>
        </ul>
    </div>
</section>