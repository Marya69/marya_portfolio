<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> -->

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
                            <img src="./img/—Pngtree—admin and customer service job_9041264.png" alt="" width="90px">
                        </div>
                        <h4 class="text-black mt-2" style="font-weight: 600;">Admin Panel</h4>
                        <?php if (isset($_SESSION['gmail'])): ?>
                        <h6 class="text-black mt-2" style="font-weight: 600;">Welcome,
                            <?php echo htmlspecialchars(explode('@', $_SESSION['gmail'])[0]); ?></h6>
                        <?php else: ?> <h6 class="text-black mt-2" style="font-weight: 600;">Welcome..</h6>
                        <?php endif; ?>
                        <hr class="border-2" style="height: 1px; background-color: black;" width="180px">
                        <ul class="nav flex-column mb-sm-auto mb-0 align-items-start align-items-sm-start" id="menu">
                            <li class="nav-item">
                                <a href="#" class="nav-link align-middle px-0 active" data-target="index">
                                    <i class="fas fa-dashboard"></i>
                                    <span class="ms-1 d-none d-sm-inline">DashBoard</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="nav-link px-0 align-middle" data-target="skills">
                                    <i class="fas fa-edit"></i>
                                    <span class="ms-1 d-none d-sm-inline">Skills</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="nav-link px-0 align-middle" data-target="project">
                                    <i class="fas fa-edit"></i>
                                    <span class="ms-1 d-none d-sm-inline">Projects</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="nav-link px-0 align-middle" data-target="experience">
                                    <i class="fas fa-edit"></i>
                                    <span class="ms-1 d-none d-sm-inline">Experiences</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="nav-link px-0 align-middle" data-target="contact">
                                    <i class="fas fa-edit"></i>
                                    <span class="ms-1 d-none d-sm-inline">Contact</span>
                                </a>
                            </li>
                            <?php if (isset($_SESSION['user_id'])): ?>
                            <li class="mt-5">
                                <a href="index.php?logout=true"
                                    class="btn btn-danger d-flex align-items-center gap-2 px-3 py-2">
                                    <i class="fas fa-sign-out-alt" style="color: white !important;"></i>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Bottom Nav for Small Screens -->
            <div class="d-block d-md-none bg-light fixed-bottom shadow py-2">
                <div class="d-flex justify-content-between px-3 bottom-nav">
                    <a href="#" class="text-center text-secondary" data-target="index">
                        <i class="fas fa-dashboard"></i>
                        <div class="small">DashBoard</div>
                    </a>
                    <?php
                    $isActive = isset($_GET['added']) ? 'active' : '';
                       ?>
                    <a href="#" class="text-center text-secondary <?= $isActive; ?>" data-target="skills">
                        <i class="fas fa-edit fa-lg"></i>
                        <div class="small"></div>
                    </a>
                    <pre><?php var_dump($isActive); ?></pre>

                    <a href="#" class="text-center text-secondary" data-target="project">
                        <i class="fas fa-edit fa-lg"></i>
                        <div class="small"></div>
                    </a>
                    <a href="#" class="text-center text-secondary" data-target="experience">
                        <i class="fas fa-edit fa-lg"></i>
                        <div class="small"></div>
                    </a>
                    <a href="#" class="text-center text-secondary" data-target="contact">
                        <i class="fas fa-edit fa-lg"></i>
                        <div class="small"></div>
                    </a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="index.php?logout=true" class="btn btn-danger d-flex align-items-center gap-2 px-3 py-2">
                        <i class="fas fa-sign-out-alt" style="color: white !important;"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col py-3" id="content">
                <!-- Main Content -->
            </div>
        </div>
    </div>

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script> -->



</body>

</html>