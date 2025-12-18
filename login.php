<!DOCTYPE html>
<html lang="en"
    dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible"
        content="IE=edge">
    <meta name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>

    <!-- Prevent the demo from appearing in search engines -->
    <meta name="robots"
        content="noindex">

    <link href="https://fonts.googleapis.com/css?family=Lato:400,700%7CRoboto:400,500%7CExo+2:600&display=swap"
        rel="stylesheet">

    <!-- Preloader -->
    <link type="text/css"
        href="assets/vendor/spinkit.css"
        rel="stylesheet">

    <!-- Perfect Scrollbar -->
    <link type="text/css"
        href="assets/vendor/perfect-scrollbar.css"
        rel="stylesheet">

    <!-- Material Design Icons -->
    <link type="text/css"
        href="assets/css/material-icons.css"
        rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link type="text/css"
        href="assets/css/fontawesome.css"
        rel="stylesheet">

    <!-- Preloader -->
    <link type="text/css"
        href="assets/css/preloader.css"
        rel="stylesheet">

    <!-- App CSS -->
    <link type="text/css"
        href="assets/css/app.css"
        rel="stylesheet">

</head>

<body class="layout-app ">

    <div class="preloader">
        <div class="sk-chase">
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
        </div>

        <!-- <div class="sk-bounce">
    <div class="sk-bounce-dot"></div>
    <div class="sk-bounce-dot"></div>
  </div> -->

        <!-- More spinner examples at https://github.com/tobiasahlin/SpinKit/blob/master/examples.html -->
    </div>

    <!-- Drawer Layout -->

    <div class="mdk-drawer-layout js-mdk-drawer-layout"
        data-push
        data-responsive-width="992px">
        <div class="mdk-drawer-layout__content page-content">

            <!-- Header -->

            <div class="navbar navbar-expand navbar-light border-bottom-2"
                id="default-navbar"
                data-primary>

                <!-- Navbar toggler -->
                <button class="navbar-toggler w-auto mr-16pt d-block d-lg-none rounded-0"
                    type="button"
                    data-toggle="sidebar">
                    <span class="material-icons">short_text</span>
                </button>

                <!-- Navbar Brand -->
                <a href="index.html"
                    class="navbar-brand mr-16pt d-lg-none">
                    <!-- <img class="navbar-brand-icon" src="assets/images/logo/white-100@2x.png" width="30" alt="Luma"> -->

                    <span class="avatar avatar-sm navbar-brand-icon mr-0 mr-lg-8pt">

                        <span class="avatar-title rounded bg-primary"><img src="assets/images/illustration/
                        /128/white.svg"
                                alt="logo"
                                class="img-fluid" /></span>

                    </span>

                    <span class="d-none d-lg-block">Luma</span>
                </a>

                
                <ul class="nav navbar-nav ml-auto mr-0">
                    <li class="nav-item">
                        <a href="dashboard.php"
                            class="btn btn-outline-danger">Back</a>
                    </li>
                </ul>
            </div>
            <!-- // END Header -->

            <!-- BEFORE Page Content -->

            <!-- // END BEFORE Page Content -->

            <!-- Page Content -->

            <div class="pt-32pt pt-sm-64pt pb-32pt">
                <div class="container page__container">
                    <h2 class="text-center">Login Admin</h2>
                    <form action="function.php" method="post"
                        class="col-md-5 p-0 mx-auto">
                        <div class="form-group">
                            <label class="form-label"
                                for="email">Email:</label>
                            <input id="email"
                            name="email"
                                type="text"
                                class="form-control"
                                placeholder="Your email address ...">
                        </div>
                        <div class="form-group">
                            <label class="form-label"
                                for="password">Password:</label>
                            <input id="password"
                                type="password"
                                name="password"
                                class="form-control"
                                placeholder="Your first and last name ...">
                           
                        </div>
                        <div class="text-center">
                            <button 
                            name="login_admin"
                            class="btn btn-primary">Login</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="page-separator justify-content-center m-0">
                <div class="page-separator__text">or Register</div>
            </div>
            <div class="bg-body pt-32pt pb-32pt pb-md-64pt text-center">
                <div class="container page__container mb-3">
                    <a href="login_siswa.php"
                        class="btn btn-dark btn-block-xs">login siswa</a>
                </div>
                <div class="container page__container">
                    <a href="registrasi.php"
                        class="btn btn-secondary btn-block-xs">Registrasi</a>
                </div>
            </div>

            <!-- // END Page Content -->

            <!-- Footer -->

            <div class="bg-white border-top-2 mt-auto">
                <div class="container page__container page-section d-flex flex-column">
                    <p class="text-70 brand mb-24pt">
                        <img class="brand-icon"
                            src="assets/images/logo/black-70@2x.png"
                            width="30"
                            alt="Luma"> Luma
                    </p>
                    <p class="measure-lead-max text-50 small mr-8pt">.</p>
                    <p class="mb-8pt d-flex">
                        <a href=""
                            class="text-70 text-underline mr-8pt small">Terms</a>
                        <a href=""
                            class="text-70 text-underline small">Privacy policy</a>
                    </p>
                    <p class="text-50 small mt-n1 mb-0">Copyright 2025 &copy; All rights reserved.</p>
                </div>
            </div>

            <!-- // END Footer -->

        </div>

        <!-- // END drawer-layout__content -->

        <!-- Drawer -->

        <!-- // END Drawer -->

    </div>

    <!-- // END Drawer Layout -->

    <!-- jQuery -->
    <script src="assets/vendor/jquery.min.js"></script>

    <!-- Bootstrap -->
    <script src="assets/vendor/popper.min.js"></script>
    <script src="assets/vendor/bootstrap.min.js"></script>

    <!-- Perfect Scrollbar -->
    <script src="assets/vendor/perfect-scrollbar.min.js"></script>

    <!-- DOM Factory -->
    <script src="assets/vendor/dom-factory.js"></script>

    <!-- MDK -->
    <script src="assets/vendor/material-design-kit.js"></script>

    <!-- App JS -->
    <script src="assets/js/app.js"></script>

    <!-- Preloader -->
    <script src="assets/js/preloader.js"></script>

</body>

</html>