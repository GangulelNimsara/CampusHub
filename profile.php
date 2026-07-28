<?php
include "includes/session.php";
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
                                <img src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='140' height='140' viewBox='0 0 140 140'><circle cx='70' cy='70' r='70' fill='%23e9ecef'/><path d='M70 35a25 25 0 1 0 0 50 25 25 0 0 0 0-50zm0 60c-23.3 0-42 12-42 25v5h84v-5c0-13-18.7-25-42-25z' fill='%236c757d'/></svg>" 
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
                                    <input type="text" class="form-control rounded-3" id="profile-first-name" name="first_name" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label small fw-semibold">Last Name</label>
                                    <input type="text" class="form-control rounded-3" id="profile-last-name" name="last_name" required>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label small fw-semibold">Email Address</label>
                                    <input type="email" class="form-control rounded-3 bg-light" id="profile-email" name="email" readonly disabled>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label small fw-semibold">Mobile Number</label>
                                    <input type="text" class="form-control rounded-3" id="profile-mobile" name="mobile" required>
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
                                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-medium">Save Profile Changes</button>
                            </div>

                        </div>
                    </div>

                </div>
            </form>

        </div>
    </main>

    <script src="assets/js/bootstrap.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/scripts.js"></script>

</body>

</html>