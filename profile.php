<?php
include "includes/session.php";
include "includes/db.php";

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION["user"]["id"];
$userQuery = Database::search("SELECT * FROM `users` WHERE `id` = '" . $userId . "'");
$user = $userQuery->fetch_assoc();
$_SESSION["user"] = $user;

$profilePic = (!empty($user['profilepicpath']) && file_exists($user['profilepicpath'])) ? $user['profilepicpath'] : 'assets/images/defultProfile.svg';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Campus Hub</title>

    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body class="bg-light">

    <?php include "includes/navbar.php"; ?>

    <main class="py-5 mt-4 min-vh-100">
        <div class="container-fluid px-4 px-md-5">

            <form id="profileForm" enctype="multipart/form-data">
                <div class="row g-4">

                    <div class="col-12 col-md-4 col-lg-3">
                        <div class="p-4 bg-white rounded-4 shadow-sm text-center h-100 border">
                            <h5 class="fw-bold mb-4">Profile Photo</h5>
                            
                            <div class="position-relative d-inline-block mb-3">
                                <img src="<?php echo htmlspecialchars($profilePic); ?>" 
                                     id="profile-img-preview" 
                                     class="rounded-circle border border-2 border-dark object-fit-cover shadow-sm" 
                                     width="140" 
                                     height="140" 
                                     alt="Profile Picture">
                                
                                <label for="profile-image-input" class="position-absolute bottom-0 end-0 btn btn-sm btn-dark rounded-circle p-2 shadow" style="cursor: pointer;" title="Change Profile Picture">
                                    📷
                                </label>
                                <input type="file" id="profile-image-input" name="profile_image" class="d-none" accept="image/*">
                            </div>
                            
                            <p class="text-muted extra-small mb-0">Click the camera icon to upload a new picture.</p>
                        </div>
                    </div>

                    <div class="col-12 col-md-8 col-lg-9">
                        <div class="p-4 bg-white rounded-4 shadow-sm h-100 border">
                            <h4 class="fw-bold mb-4">Account Information</h4>

                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label small fw-semibold">First Name</label>
                                    <input type="text" class="form-control rounded-3" id="profile-first-name" name="first_name" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label small fw-semibold">Last Name</label>
                                    <input type="text" class="form-control rounded-3" id="profile-last-name" name="last_name" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label small fw-semibold">Email Address</label>
                                    <input type="email" class="form-control rounded-3 bg-light" id="profile-email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" readonly disabled>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label small fw-semibold">Mobile Number</label>
                                    <input type="text" class="form-control rounded-3" id="profile-mobile" name="mobile" value="<?php echo htmlspecialchars($user['mobile'] ?? ''); ?>">
                                </div>
                            </div>

                            <hr class="my-4">

                            <h5 class="fw-bold mb-3">Security & Password</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-12 col-md-6">
                                    <label class="form-label small fw-semibold">New Password <span class="text-muted fw-normal">(Leave blank to keep current)</span></label>
                                    <input type="password" class="form-control rounded-3" id="profile-new-password" name="new_password" placeholder="••••••••">
                                </div>
                            </div>

                            <div class="text-start">
                                <button type="button" onclick="updateProfileProcess();" class="btn btn-primary rounded-pill px-5 fw-medium">Save Profile Changes</button>
                            </div>

                        </div>
                    </div>

                </div>
            </form>

        </div>
    </main>

    <?php include "includes/footer.php"; ?>

    <script src="assets/js/bootstrap.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/scripts.js"></script>

</body>

</html>