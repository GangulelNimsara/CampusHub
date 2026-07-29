<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION["user"]);

$navProfilePic = "assets/images/defultProfile.svg";
if ($isLoggedIn && !empty($_SESSION['user']['profilepicpath'])) {
    $dbPicPath = $_SESSION['user']['profilepicpath'];
    if (!empty($dbPicPath)) {
        $navProfilePic = $dbPicPath;
    }
}
?>

<nav class="navbar navbar-expand-lg fixed-top bg-light border-bottom border-2 border-dark py-2">
    <div class="container-fluid">

        <a href="index.php" class="navbar-brand">
            <img src="assets/images/logo.png" alt="Campus Hub" style="max-height:60px;">
        </a>

        <button class="navbar-toggler" type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#mobileMenu"
            aria-controls="mobileMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse">

            <ul class="navbar-nav mx-auto gap-lg-4">

                <li class="nav-item">
                    <a class="nav-link active" href="index.php">
                        Home
                        <img src="assets/images/home.svg" width="20">
                    </a>
                </li>

                <?php if ($isLoggedIn): ?>
                    <li class="nav-item">
                        <a class="nav-link fw-bold text-primary" href="dashboard.php">
                            Dashboard
                            <img src="assets/images/dashboard.svg" width="20">
                        </a>
                    </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a class="nav-link" href="events.php">
                        Events
                        <img src="assets/images/events.svg" width="20">
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="gallery.php">
                        Gallery
                        <img src="assets/images/gallery.svg" width="20">
                    </a>
                </li>

            </ul>

            <div class="d-flex align-items-center gap-3">

                <?php if (!$isLoggedIn): ?>
                    <a href="register.php"
                        class="nav-link border border-dark rounded-pill px-4 py-2 animation-hover">
                        Register
                        <img src="assets/images/register.svg" class="svg ms-2" width="20">
                    </a>

                    <a href="login.php"
                        class="nav-link border border-dark rounded-pill px-4 py-2 animation-hover">
                        Login
                        <img src="assets/images/login.svg" class="svg ms-2" width="20">
                    </a>
                <?php else: ?>
                    <div class="dropdown">
                        <a href="#" class="nav-link border border-dark rounded-pill px-3 py-1 animation-hover d-inline-flex align-items-center gap-2" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="rounded-circle border border-dark overflow-hidden d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <img src="<?php echo htmlspecialchars($navProfilePic); ?>" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <span class="fw-semibold text-dark"><?php echo htmlspecialchars($_SESSION["user"]["first_name"] ?? $_SESSION["user"]["username"] ?? "User"); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-2 border-dark rounded-3 mt-2" aria-labelledby="profileDropdown">
                            <li><a class="dropdown-item d-flex align-items-center gap-2 fw-medium" href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                            <li><a class="dropdown-item d-flex align-items-center gap-2 fw-medium" href="profile.php"><i class="bi bi-person"></i> Update Profile</a></li>
                            <li><a class="dropdown-item d-flex align-items-center gap-2 fw-medium" href="events.php"><i class="bi bi-calendar-event"></i> Event Calendar</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item d-flex align-items-center gap-2 text-danger fw-semibold" href="logoutProcess.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                        </ul>
                    </div>
                <?php endif; ?>

            </div>

        </div>

    </div>
</nav>

<div class="offcanvas offcanvas-end" tabindex="-1" id="mobileMenu">

    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title">
            <span class="text-primary">Campus</span><span class="text-warning">Hub</span>
        </h5>
        <button class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body">

        <ul class="navbar-nav">

            <li class="nav-item">
                <a class="nav-link active" href="index.php">
                    Home
                    <img src="assets/images/home.svg" width="20">
                </a>
            </li>

            <?php if ($isLoggedIn): ?>
                <li class="nav-item">
                    <a class="nav-link fw-bold text-primary" href="dashboard.php">
                        Dashboard
                        <img src="assets/images/dashboard.svg" width="20">
                    </a>
                </li>
            <?php endif; ?>

            <li class="nav-item">
                <a class="nav-link" href="events.php">
                    Events
                    <img src="assets/images/events.svg" width="20">
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="gallery.php">
                    Gallery
                    <img src="assets/images/gallery.svg" width="20">
                </a>
            </li>

            <?php if (!$isLoggedIn): ?>
                <li class="nav-item mt-4">
                    <a href="register.php"
                        class="nav-link border border-dark rounded-pill py-2 text-center animation-hover">
                        Register
                    </a>
                </li>

                <li class="nav-item mt-3">
                    <a href="login.php"
                        class="nav-link border border-dark rounded-pill py-2 text-center animation-hover">
                        Login
                    </a>
                </li>
            <?php else: ?>
                <li class="nav-item mt-4 d-flex justify-content-center align-items-center gap-2">
                    <a href="profile.php"
                        class="border border-dark rounded-circle overflow-hidden d-flex justify-content-center align-items-center"
                        style="width:45px;height:45px;">
                        <img src="<?php echo htmlspecialchars($navProfilePic); ?>" style="width:100%;height:100%;object-fit:cover;">
                    </a>
                    <span class="fw-bold text-dark"><?php echo htmlspecialchars($_SESSION["user"]["first_name"] ?? $_SESSION["user"]["username"] ?? "User"); ?></span>
                </li>
                <li class="nav-item mt-2 text-center">
                    <a href="logoutProcess.php" class="text-danger fw-semibold text-decoration-none">Logout</a>
                </li>
            <?php endif; ?>

        </ul>

    </div>

</div>