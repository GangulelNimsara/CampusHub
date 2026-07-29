<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$adminName = $_SESSION["admin"]["username"] ?? "Admin";

$current_page = basename($_SERVER['PHP_SELF']);
$current_dir = basename(dirname($_SERVER['PHP_SELF']));

function getNavLink($targetDir, $targetPage, $currentDir, $currentPage) {
    if ($currentDir === $targetDir && $currentPage === $targetPage) {
        return "index.php";
    }
    if ($currentDir === 'admin') {
        return $targetDir . '/' . $targetPage;
    }
    return '../' . $targetDir . '/' . $targetPage;
}

$dashboardLink = ($current_dir === 'admin') ? 'dashboard.php' : '../dashboard.php';
$studentsLink = getNavLink('students', 'index.php', $current_dir, $current_page);
$eventsLink = getNavLink('events', 'index.php', $current_dir, $current_page);
$registrationsLink = getNavLink('registrations', 'index.php', $current_dir, $current_page);
$announcementsLink = getNavLink('announcements', 'index.php', $current_dir, $current_page);
$mediaLink = getNavLink('media', 'index.php', $current_dir, $current_page);
$logoutLink = ($current_dir === 'admin') ? 'logout.php' : '../logout.php';
$studentSiteLink = ($current_dir === 'admin') ? '../index.php' : '../../index.php';
$logoLink = ($current_dir === 'admin') ? '../assets/images/logo.png' : '../../assets/images/logo.png';
?>

<nav class="navbar navbar-expand-lg bg-white border-bottom border-2 border-dark fixed-top py-2 px-3">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?php echo $dashboardLink; ?>">
            <img src="<?php echo $logoLink; ?>" alt="Campus Hub" style="max-height: 45px;">
            <span class="badge bg-dark text-white rounded-pill px-2 py-1 extra-small ms-1">Admin</span>
        </a>

        <button class="navbar-toggler border-dark" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbarContent" aria-controls="adminNavbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNavbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-lg-2 ms-lg-4">
                <li class="nav-item">
                    <a class="nav-link fw-semibold text-dark" href="<?php echo $dashboardLink; ?>">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold text-dark" href="<?php echo $studentsLink; ?>">Students</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold text-dark" href="<?php echo $eventsLink; ?>">Events</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold text-dark" href="<?php echo $registrationsLink; ?>">Registrations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold text-dark" href="<?php echo $announcementsLink; ?>">Announcements</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold text-dark" href="<?php echo $mediaLink; ?>">Media</a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-3">
                <a href="<?php echo $studentSiteLink; ?>" target="_blank" class="btn btn-outline-dark btn-sm rounded-pill fw-medium px-3">
                    🌐 Student Site
                </a>

                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle fw-bold" id="adminNavDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="rounded-circle bg-dark text-white d-inline-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <span><?php echo htmlspecialchars($adminName); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-2 border-dark rounded-3 mt-2" aria-labelledby="adminNavDropdown">
                        <li><a class="dropdown-item text-danger fw-bold" href="<?php echo $logoutLink; ?>">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>