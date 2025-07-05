<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
    .circle img {
        border-radius: 50%;
    }


    .div-social-media a {
        border-radius: 50%;
        padding: 5px;
        font-size: 12px;
        width: 30px;
        height: 30px;
        margin-right: 8px;
    }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <!-- Sidebar for Large Screens -->
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 d-none d-md-block">
                <div
                    class="d-flex flex-column align-items-center align-items-sm-center px-3 pt-2 text-white min-vh-100 bg-light">
                    <!-- Sidebar Content -->
                    <div class="top-sidebar d-flex flex-column align-items-center">
                        <div class="circle">
                            <img src="./img/girl.png" alt="" width="90px">
                        </div>
                        <h6 class="text-black mt-2" style="font-weight: 600;">Marya Jabar</h6>
                        <p class="mb-0" style="font-size: 0.9rem;">Web Developer</p>
                        <div class="row mt-2 div-social-media">
                            <a class="btn btn-outline-dark btn-floating d-flex align-items-center justify-content-center"
                                href="https://www.linkedin.com/in/maria-j-1aa706266?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=android_app"
                                target="_blank" role="button">
                                <i class="fab fa-linkedin"></i>
                            </a>
                            <a class="btn btn-outline-dark btn-floating d-flex align-items-center justify-content-center"
                                href="https://t.me/Maria_Jabar" target="_blank" role="button">
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
                        <hr class="border-2" style="height: 1px; background-color: black;" width="180px">
                        <ul class="nav flex-column mb-sm-auto mb-0 align-items-start align-items-sm-start" id="menu">
                            <li class="nav-item">
                                <a href="#" class="nav-link align-middle px-0 active" data-target="about-me">
                                    <i class="fas fa-user"></i>
                                    <span class="ms-1 d-none d-sm-inline">About Me</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="nav-link px-0 align-middle" data-target="skills">
                                    <i class="fas fa-tools"></i>
                                    <span class="ms-1 d-none d-sm-inline">Skills & Abilities</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="nav-link px-0 align-middle" data-target="education">
                                    <i class="fas fa-graduation-cap"></i>
                                    <span class="ms-1 d-none d-sm-inline">My Education</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="nav-link px-0 align-middle" data-target="projects">
                                    <i class="fas fa-tasks"></i>
                                    <span class="ms-1 d-none d-sm-inline">Projects Made</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="nav-link px-0 align-middle" data-target="experience">
                                    <i class="fas fa-briefcase"></i>
                                    <span class="ms-1 d-none d-sm-inline">Experience</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="nav-link px-0 align-middle" data-target="contact">
                                    <i class="fas fa-phone"></i>
                                    <span class="ms-1 d-none d-sm-inline">Contact</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Bottom Nav for Small Screens -->
            <div class="d-block d-md-none bg-light fixed-bottom shadow py-2 ">
                <div class="d-flex justify-content-between px-3 bottom-nav">
                    <a href="#" class="text-center text-secondary active" data-target="about-me">

                        <i class="fas fa-user fa-lg"></i>
                        <div class="small"></div>
                    </a>
                    <a href="#" class="text-center text-secondary" data-target="skills">
                        <i class="fas fa-tools fa-lg"></i>
                        <div class="small"></div>
                    </a>
                    <a href="#" class="text-center text-secondary" data-target="education">
                        <i class="fas fa-graduation-cap fa-lg"></i>
                        <div class="small"></div>
                    </a>
                    <a href="#" class="text-center text-secondary" data-target="projects">
                        <i class="fas fa-tasks fa-lg"></i>
                        <div class="small"></div>
                    </a>
                    <a href="#" class="text-center text-secondary" data-target="experience">
                        <i class="fas fa-briefcase fa-lg"></i>
                        <div class="small"></div>
                    </a>
                    <a href="#" class="text-center text-secondary" data-target="contact">
                        <i class="fas fa-phone fa-lg"></i>
                        <div class="small"></div>
                    </a>
                </div>
            </div>

            <div class="col py-3" id="content">
                <!-- Main Content -->
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>