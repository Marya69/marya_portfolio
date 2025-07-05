<?php

include './admin_panel/config.php';

$query = "SELECT phone_n, gmail FROM contacts WHERE id = 1"; // Use actual ID or condition
$result = mysqli_query($conn, $query);
$contact = mysqli_fetch_assoc($result);

// Fallback values if not set
$phone = $contact['phone_n'] ?? '';
$gmail = $contact['gmail'] ?? '';
?>
<section id="contact" class="p-3 section">
    <video autoplay muted loop>
        <source src="img/0_abstract_background_3840x2160.webm" type="video/mp4">
    </video>
    <!-- <h1 class="mt-3" style="color: #3498DB !important;">Contact</h1> -->
    <div class="container">
        <div class="row">
            <div class=" mt-4 px-5" style="text-align: center;">
                <h1 class="mt-3" style="color: #3498DB !important;">Contact</h1>
                <!-- <h2>Contact Information</h2> -->

                <p class="mt-3 px-3">Interested in hiring me for your project or just want to say hi? You can send me
                    <br>
                    an email to <a
                        href="mailto:<?php echo htmlspecialchars($gmail); ?>"><?php echo htmlspecialchars($gmail); ?></a>
                    .Want to get
                    connected? Follow me on the social channels below.
                </p>
                <div class="row mt-2 div-social-media"
                    style="display: flex !important;padding: 2% !important;justify-content: center !important;align-items: center !important;text-align: center;text-align: center;">
                    <a class="btn btn-outline-dark btn-floating d-flex align-items-center justify-content-center"
                        href="https://www.linkedin.com/in/maria-j-1aa706266?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=android_app"
                        target="_blank" role="button">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <a class="btn btn-outline-dark btn-floating d-flex align-items-center justify-content-center"
                        href="https://t.me/maria_jabar" target="_blank" role="button">
                        <i class="fab fa-telegram"></i>
                    </a>
                    <a class="btn btn-outline-dark btn-floating d-flex align-items-center justify-content-center"
                        href="https://www.facebook.com/marya63/" target="_blank" role="button">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a class="btn btn-outline-dark btn-floating d-flex align-items-center justify-content-center"
                        href="https://wa.me/9647508507224" target="_blank" role="button">
                        <!-- <i class="fa-brands fab-square-whatsapp"></i> -->
                        <img src="./img/whatsapp.png" width="11px" alt="whats app" class="whatsapp-img">

                    </a>
                </div>





            </div>

            <hr>
            <div class="container">
                <div class="row">
                    <div class="border-start border-3  p-3 ms-2 "
                        style="font-size: 3.25rem !important;font-weight: bold;border-left-color: #3498DB !important;">
                        Contact Details
                    </div>


                </div>
                <div class="row mt-4">
                    <div class="col-md-4">


                        <div class="border-start border-3  p-3  mt-3"
                            style="font-size: 1.5rem; border-color: #3498DB !important;">
                            <i class="fas fa-phone-alt me-2" style="font-size: 2rem;color: #3498DB !important;"></i>
                            +964 <?php echo htmlspecialchars($phone); ?>
                        </div>

                    </div>

                    <div class="col-md-5">
                        <div class="border-start border-3  p-3 mt-3"
                            style="font-size: 1.5rem;border-color: #3498DB !important;">
                            <i class="fas fa-envelope me-2" style="font-size: 2rem; color: #3498DB !important;"></i>
                            <?php echo htmlspecialchars($gmail); ?>
                        </div>
                    </div>



                    <div class="col-md-3">
                        <div class="border-start border-3  p-3  mt-3"
                            style="font-size: 1.5rem;border-color: #3498DB !important;">
                            <i class="fas fa-map-marker-alt me-2"
                                style="font-size: 2rem;color: #3498DB !important;"></i> Sulaimani, Iraq
                        </div>
                    </div>

                </div>
            </div>
            <div class="container mt-5">
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-center align-items-center ">
                        <p>Copyright © 2025 Marya Jabar. All Rights Reserved.</p>
                    </div>
                </div>
</section>