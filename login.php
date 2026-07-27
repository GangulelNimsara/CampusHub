<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>CampusHub Login</title>
</head>
<body class="m-0 bg-white">

<div class="container-fluid p-0"> 
    <div class="row g-0 min-vh-100"> 

        <div class="d-none d-lg-block col-lg-6 position-relative image-section"> 
            <div class="split-bg"></div>
            
            <div class="position-absolute top-0 start-0 m-4">
                <a href="register.php" class="slide-arrow-btn d-inline-flex align-items-center gap-2 px-4 py-2 text-decoration-none">
                    <span class="small fw-semibold text-white">Create Account</span>
                    <svg class="arrow-icon-right" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="col-12 col-lg-6 d-flex align-items-center justify-content-center px-4 px-md-5 py-4 page-content bg-white">
            <div class="w-100" style="max-width: 400px;"> 
                
                <div class="text-center mb-3">
                    <img src="assets/images/logo.png" width="130">
                </div>

                <h2 class="text-center fw-bold text-primary mb-3">Login</h2>
                
                <div id="loginMessage" class="w-100"></div>
                
                <form id="loginForm" onsubmit="event.preventDefault(); loginProcess();">
                    <div class="mb-3">
                        <label for="username" class="form-label text-muted">Username</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label for="password" class="form-label text-muted">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <a href="forgetPassword.php">Forget Password?</a>
                    </div>


                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">Login</button>
                </form>
                
                <div class="text-center mt-3 d-lg-none">
                    <span class="text-muted">New here?</span> 
                    <a href="register.php" class="fw-bold text-decoration-none">Create an account</a>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="assets/js/bootstrap.bundle.js"></script>
<script src="assets/js/scripts.js"></script>
</body>
</html>