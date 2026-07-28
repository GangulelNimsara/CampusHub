<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Header</title>
</head>

<body>

    <nav class="navbar navbar-expand-lg bg-light border-bottom border-2 border-dark d-none d-lg-flex py-2">
        <div class="container-fluid">
            <div class="row w-100 align-items-center m-0">

                <div class="col-3 d-flex justify-content-start">
                    <a href="../index.php" class="navbar-brand m-0 p-0">
                        <img src="../assets/images/logo.png" alt="Logo" class="img-fluid" style="max-height: 60px;">
                    </a>
                </div>

                <div class="col-5 d-flex justify-content-center">
                    <ul class="navbar-nav flex-row gap-4 mb-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="../index.php">Home <img src="../assets/images/home.svg" width="20"></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../events.php">Events <img src="../assets/images/events.svg" width="20"></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../gallery.php">Gallery <img src="../assets/images/gallery.svg" width="20"></a>
                        </li>
                    </ul>
                </div>

                <div class="col-4 d-flex justify-content-end align-items-center">
                    <a href="../register.php" class="nav-link border border-dark rounded-pill py-2 px-4 me-3 animation-hover">
                        Register <img src="../assets/images/register.svg" class="svg ms-2" width="20">
                    </a>
                    <a href="../login.php" class="nav-link border border-dark rounded-pill py-2 px-4 me-3 animation-hover">
                        Login <img src="../assets/images/login.svg" class="svg ms-2" width="20">
                    </a>
                    <a href="../profile.php" class="nav-link border border-dark rounded-circle p-0 animation-hover d-inline-flex align-items-center justify-content-center overflow-hidden" style="width: 40px; height: 40px; min-width: 40px; min-height: 40px; flex-shrink: 0;">
                        <img src="../assets/images/defultProfile.svg" alt="Profile" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                    </a>
                </div>

            </div>
        </div>
    </nav>

    <nav class="navbar bg-body-tertiary fixed-top border-bottom border-2 border-dark d-flex d-lg-none py-2">
        <div class="container-fluid">
            <div class="row w-100 align-items-center m-0">

                <div class="col-8 d-flex justify-content-start">
                    <a href="../index.php" class="navbar-brand m-0 p-0">
                        <img src="../assets/images/logo.png" alt="Logo" class="img-fluid" style="max-height: 55px;">
                    </a>
                </div>
                <div class="col-4 d-flex justify-content-end">

                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                        <div class="offcanvas-header border-bottom">
                            <h1 class="offcanvas-title fs-4" id="offcanvasNavbarLabel">
                                <span class="text-primary">Campus</span><span class="text-warning">Hub</span>
                            </h1>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="../index.php">Home <img src="../assets/images/home.svg" width="20"></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../events.php">Events <img src="../assets/images/events.svg" width="20"></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../gallery.php">Gallery <img src="../assets/images/gallery.svg" width="20"></a>
                                </li>
                                <li class="nav-item mt-4">
                                    <a href="../register.php" class="nav-link border border-dark rounded-pill py-2 px-4 animation-hover text-center">
                                        Register <img src="../assets/images/register.svg" class="svg ms-2" width="20">
                                    </a>
                                </li>
                                <li class="nav-item mt-2">
                                    <a href="../login.php" class="nav-link border border-dark rounded-pill py-2 px-4 animation-hover text-center">
                                        Login <img src="../assets/images/login.svg" class="svg ms-2" width="20">
                                    </a>
                                </li>
                                <li class="nav-item mt-2 d-flex justify-content-center">
                                    <a href="../profile.php" class="nav-link border border-dark rounded-circle p-0 animation-hover d-inline-flex align-items-center justify-content-center overflow-hidden" style="width: 40px; height: 40px; min-width: 40px; min-height: 40px; flex-shrink: 0;">
                                        <img src="../assets/images/defultProfile.svg" alt="Profile" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <script src="../assets/js/bootstrap.bundle.js"></script>
</body>

</html>