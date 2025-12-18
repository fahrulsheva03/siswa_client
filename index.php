<?php 
require 'koneksi.php';

if(!isset($_SESSION['login_siswa'])){
     echo "<script>alert('Anda belum login!');
     window.location.href='login_siswa.php';</script>";
     exit;
}

if(isset($_SESSION['login_siswa'])){
     echo "
     <script>
     window.location.href='dashboard.php';</script>";
     exit;
}

?>

<!DOCTYPE html>
<html lang="en"
    dir="ltr">
<!-- index_backup -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible"
        content="IE=edge">
    <meta name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Tes</title>

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

                        <span class="avatar-title rounded bg-primary"><img src="assets/images/illustration/student/128/white.svg"
                                alt="logo"
                                class="img-fluid" /></span>

                    </span>

                    <span class="d-none d-lg-block">Luma</span>
                </a>

                <ul class="nav navbar-nav d-none d-sm-flex flex justify-content-start ml-8pt">
                    <li class="nav-item active">
                        <a href="index.html"
                            class="nav-link">Home</a>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a href="#"
                            class="nav-link dropdown-toggle"
                            data-toggle="dropdown"
                            data-caret="false">Paths</a>
                        <div class="dropdown-menu">
                            <a href="paths.html"
                                class="dropdown-item">Browse Paths</a>
                            <a href="student-path.html"
                                class="dropdown-item">Path Details</a>
                            <a href="student-path-assessment.html"
                                class="dropdown-item">Skill Assessment</a>
                            <a href="student-path-assessment-result.html"
                                class="dropdown-item">Skill Result</a>
                            <a href="student-paths.html"
                                class="dropdown-item">My Paths</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="pricing.html"
                            class="nav-link">Pricing</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#"
                            class="nav-link dropdown-toggle"
                            data-toggle="dropdown"
                            data-caret="false">Teachers</a>
                        <div class="dropdown-menu">
                            <a href="instructor-dashboard.html"
                                class="dropdown-item">Instructor Dashboard</a>
                            <a href="instructor-courses.html"
                                class="dropdown-item">Manage Courses</a>
                            <a href="instructor-quizzes.html"
                                class="dropdown-item">Manage Quizzes</a>
                            <a href="instructor-earnings.html"
                                class="dropdown-item">Earnings</a>
                            <a href="instructor-statement.html"
                                class="dropdown-item">Statement</a>
                            <a href="instructor-edit-course.html"
                                class="dropdown-item">Edit Course</a>
                            <a href="instructor-edit-quiz.html"
                                class="dropdown-item">Edit Quiz</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown"
                        data-toggle="tooltip"
                        data-title="Community"
                        data-placement="bottom"
                        data-boundary="window">
                        <a href="#"
                            class="nav-link dropdown-toggle"
                            data-toggle="dropdown"
                            data-caret="false">
                            <i class="material-icons">people_outline</i>
                        </a>
                        <div class="dropdown-menu">
                            <a href="teachers.html"
                                class="dropdown-item">Browse Teachers</a>
                            <a href="student-profile.html"
                                class="dropdown-item">Student Profile</a>
                            <a href="teacher-profile.html"
                                class="dropdown-item">Instructor Profile</a>
                            <a href="blog.html"
                                class="dropdown-item">Blog</a>
                            <a href="blog-post.html"
                                class="dropdown-item">Blog Post</a>
                            <a href="faq.html"
                                class="dropdown-item">FAQ</a>
                            <a href="help-center.html"
                                class="dropdown-item">Help Center</a>
                            <a href="discussions.html"
                                class="dropdown-item">Discussions</a>
                            <a href="discussion.html"
                                class="dropdown-item">Discussion Details</a>
                            <a href="discussions-ask.html"
                                class="dropdown-item">Ask Question</a>
                        </div>
                    </li>
                </ul>

                <ul class="nav navbar-nav ml-auto mr-0">
                    <li class="nav-item">
                        <a href="login.html"
                            class="nav-link"
                            data-toggle="tooltip"
                            data-title="Login"
                            data-placement="bottom"
                            data-boundary="window"><i class="material-icons">lock_open</i></a>
                    </li>
                    <li class="nav-item">
                        <a href="login.php"
                            class="btn btn-outline-dark">Login</a>
                    </li>
                </ul>
            </div>

            <!-- // END Header -->

            <!-- BEFORE Page Content -->

            <!-- // END BEFORE Page Content -->

            <!-- Page Content -->

            <div class="mdk-box mdk-box--bg-primary bg-dark js-mdk-box mb-0"
                data-effects="parallax-background blend-background">
                <div class="mdk-box__bg">
                    <div class="mdk-box__bg-front"
                        style="background-image: url(assets/images/photodune-4161018-group-of-students-m.jpg);"></div>
                </div>
                <div class="mdk-box__content justify-content-center">
                    <div class="hero container page__container text-center py-112pt">
                        <h1 class="text-white text-shadow">Selamat Datang Di Kelas</h1>
                        <p class="lead measure-hero-lead mx-auto text-white text-shadow mb-48pt">Business, Technology and Creative Skills taught by industry experts. Explore a wide range of skills with our professional tutorials.</p>

                        <a href="courses.html"
                            class="btn btn-lg btn-white btn--raised mb-16pt">Temukan Kelas Anda</a>

                        <p class="mb-0"><a href=""
                                class="text-white text-shadow"><strong>Are you a teacher?</strong></a></p>

                    </div>
                </div>
            </div>

            <div class="border-bottom-2 py-16pt navbar-light bg-white border-bottom-2">
                <div class="container page__container">
                    <div class="row align-items-center">
                        <div class="d-flex col-md align-items-center border-bottom border-md-0 mb-16pt mb-md-0 pb-16pt pb-md-0">
                            <div class="rounded-circle bg-primary w-64 h-64 d-inline-flex align-items-center justify-content-center mr-16pt">
                                <i class="material-icons text-white">subscriptions</i>
                            </div>
                            <div class="flex">
                                <div class="card-title mb-4pt">8,000+ Courses</div>
                                <p class="card-subtitle text-70">Explore a wide range of skills.</p>
                            </div>
                        </div>
                        <div class="d-flex col-md align-items-center border-bottom border-md-0 mb-16pt mb-md-0 pb-16pt pb-md-0">
                            <div class="rounded-circle bg-primary w-64 h-64 d-inline-flex align-items-center justify-content-center mr-16pt">
                                <i class="material-icons text-white">verified_user</i>
                            </div>
                            <div class="flex">
                                <div class="card-title mb-4pt">By Industry Experts</div>
                                <p class="card-subtitle text-70">Professional development from the best people.</p>
                            </div>
                        </div>
                        <div class="d-flex col-md align-items-center">
                            <div class="rounded-circle bg-primary w-64 h-64 d-inline-flex align-items-center justify-content-center mr-16pt">
                                <i class="material-icons text-white">update</i>
                            </div>
                            <div class="flex">
                                <div class="card-title mb-4pt">Unlimited Access</div>
                                <p class="card-subtitle text-70">Unlock Library and learn any topic with one subscription.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-section border-bottom-2">
                <div class="container page__container">

                    <div class="page-separator">
                        <div class="page-separator__text">From the blog</div>
                    </div>

                    <div class="row card-group-row">

                        <div class="col-md-6 col-lg-4 card-group-row__col">

                            <div class="card card--elevated posts-card-popular overlay card-group-row__card">
                                <img src="assets/images/paths/sketch_430x168.png"
                                    alt=""
                                    class="card-img">
                                <div class="fullbleed bg-primary"
                                    style="opacity: .5"></div>
                                <div class="posts-card-popular__content">
                                    <div class="card-body d-flex align-items-center">
                                        <div class="avatar-group flex">
                                            <div class="avatar avatar-xs"
                                                data-toggle="tooltip"
                                                data-placement="top"
                                                title="Janell D.">
                                                <a href=""><img src="assets/images/256_luke-porter-261779-unsplash.jpg"
                                                        alt="Avatar"
                                                        class="avatar-img rounded-circle"></a>
                                            </div>
                                        </div>
                                        <a style="text-decoration: none;"
                                            class="d-flex align-items-center"
                                            href=""><i class="material-icons mr-1"
                                                style="font-size: inherit;">remove_red_eye</i> <small>327</small></a>
                                    </div>
                                    <div class="posts-card-popular__title card-body">
                                        <small class="text-muted text-uppercase">sketch</small>
                                        <a class="card-title"
                                            href="blog-post.html">Merge Duplicates Inconsistent Symbols</a>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-6 col-lg-4 card-group-row__col">

                            <div class="card card--elevated posts-card-popular overlay card-group-row__card">
                                <img src="assets/images/paths/invision_430x168.png"
                                    alt=""
                                    class="card-img">
                                <div class="fullbleed bg-primary"
                                    style="opacity: .5"></div>
                                <div class="posts-card-popular__content">
                                    <div class="card-body d-flex align-items-center">
                                        <div class="avatar-group flex">
                                            <div class="avatar avatar-xs"
                                                data-toggle="tooltip"
                                                data-placement="top"
                                                title="Janell D.">
                                                <a href=""><img src="assets/images/256_michael-dam-258165-unsplash.jpg"
                                                        alt="Avatar"
                                                        class="avatar-img rounded-circle"></a>
                                            </div>
                                        </div>
                                        <a style="text-decoration: none;"
                                            class="d-flex align-items-center"
                                            href=""><i class="material-icons mr-1"
                                                style="font-size: inherit;">remove_red_eye</i> <small>327</small></a>
                                    </div>
                                    <div class="posts-card-popular__title card-body">
                                        <small class="text-muted text-uppercase">invision</small>
                                        <a class="card-title"
                                            href="blog-post.html">Design Systems Essentials</a>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-6 col-lg-4 card-group-row__col">

                            <div class="card card--elevated posts-card-popular overlay card-group-row__card">
                                <img src="assets/images/paths/photoshop_430x168.png"
                                    alt=""
                                    class="card-img">
                                <div class="fullbleed bg-primary"
                                    style="opacity: .5"></div>
                                <div class="posts-card-popular__content">
                                    <div class="card-body d-flex align-items-center">
                                        <div class="avatar-group flex">
                                            <div class="avatar avatar-xs"
                                                data-toggle="tooltip"
                                                data-placement="top"
                                                title="Janell D.">
                                                <a href=""><img src="assets/images/256_rsz_1andy-lee-642320-unsplash.jpg"
                                                        alt="Avatar"
                                                        class="avatar-img rounded-circle"></a>
                                            </div>
                                        </div>
                                        <a style="text-decoration: none;"
                                            class="d-flex align-items-center"
                                            href=""><i class="material-icons mr-1"
                                                style="font-size: inherit;">remove_red_eye</i> <small>327</small></a>
                                    </div>
                                    <div class="posts-card-popular__title card-body">
                                        <small class="text-muted text-uppercase">photoshop</small>
                                        <a class="card-title"
                                            href="blog-post.html">Semantic Logo Design</a>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="posts-cards">

                        <div class="card posts-card mb-0">
                            <div class="posts-card__content d-flex align-items-center flex-wrap">
                                <div class="avatar avatar-lg mr-3">
                                    <a href="blog-post.html"><img src="assets/images/paths/invision_200x168.png"
                                            alt="avatar"
                                            class="avatar-img rounded"></a>
                                </div>
                                <div class="posts-card__title flex d-flex flex-column">
                                    <a href="blog-post.html"
                                        class="card-title mr-3">Design Systems Essentials</a>
                                    <small class="text-50">35 views last week</small>
                                </div>
                                <div class="d-flex align-items-center flex-column flex-sm-row posts-card__meta">
                                    <div class="mr-3 text-50 text-uppercase posts-card__tag d-flex align-items-center">
                                        <i class="material-icons text-muted-light mr-1">folder_open</i> inVision
                                    </div>
                                    <div class="mr-3 text-50 posts-card__date">
                                        <small>11 Nov, 2018 07:46 AM</small>
                                    </div>
                                    <div class="media ml-sm-auto align-items-center">
                                        <div class="media-left mr-2 avatar-group">

                                            <div class="avatar avatar-xs"
                                                data-toggle="tooltip"
                                                data-placement="top"
                                                title="Janell D.">
                                                <img src="assets/images/256_rsz_1andy-lee-642320-unsplash.jpg"
                                                    alt="Avatar"
                                                    class="avatar-img rounded-circle">
                                            </div>

                                            <div class="avatar avatar-xs"
                                                data-toggle="tooltip"
                                                data-placement="top"
                                                title="Janell D.">
                                                <img src="assets/images/256_michael-dam-258165-unsplash.jpg"
                                                    alt="Avatar"
                                                    class="avatar-img rounded-circle">
                                            </div>

                                            <div class="avatar avatar-xs"
                                                data-toggle="tooltip"
                                                data-placement="top"
                                                title="Janell D.">
                                                <img src="assets/images/256_luke-porter-261779-unsplash.jpg"
                                                    alt="Avatar"
                                                    class="avatar-img rounded-circle">
                                            </div>

                                        </div>
                                        <div class="media-body">

                                            <a href="">+2 more</a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <div class="page-section border-bottom-2">
                <div class="container page__container">
                    <div class="page-separator">
                        <div class="page-separator__text">Learning Paths</div>
                    </div>

                    <div class="row card-group-row">

                        <div class="col-sm-4 card-group-row__col">

                            <div class="card js-overlay card-sm overlay--primary-dodger-blue stack stack--1 card-group-row__card"
                                data-toggle="popover"
                                data-trigger="click">

                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex align-items-center">
                                        <div class="flex">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded mr-12pt z-0 o-hidden">
                                                    <div class="overlay">
                                                        <img src="assets/images/paths/react_40x40@2x.png"
                                                            width="40"
                                                            height="40"
                                                            alt="Angular"
                                                            class="rounded">
                                                        <span class="overlay__content overlay__content-transparent">
                                                            <span class="overlay__action d-flex flex-column text-center lh-1">
                                                                <small class="h6 small text-white mb-0"
                                                                    style="font-weight: 500;">80%</small>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="flex">
                                                    <div class="card-title">React Native</div>
                                                    <p class="flex text-50 lh-1 mb-0"><small>18 courses</small></p>
                                                </div>
                                            </div>
                                        </div>

                                        <a href="student-path.html"
                                            data-toggle="tooltip"
                                            data-title="Add Favorite"
                                            data-placement="top"
                                            data-boundary="window"
                                            class="ml-4pt material-icons text-20 card-course__icon-favorite">favorite_border</a>

                                    </div>

                                </div>
                            </div>

                            <div class="popoverContainer d-none">
                                <div class="media">
                                    <div class="media-left mr-12pt">
                                        <img src="assets/images/paths/react_40x40@2x.png"
                                            width="40"
                                            height="40"
                                            alt="Angular"
                                            class="rounded">
                                    </div>
                                    <div class="media-body">
                                        <div class="card-title">React Native</div>
                                        <p class="text-50 d-flex lh-1 mb-0 small">18 courses</p>
                                    </div>
                                </div>

                                <p class="mt-16pt text-70">Learn the fundamentals of working with React Native and how to create basic applications.</p>

                                <div class="my-32pt">
                                    <div class="d-flex align-items-center mb-8pt justify-content-center">
                                        <div class="d-flex align-items-center mr-8pt">
                                            <span class="material-icons icon-16pt text-50 mr-4pt">access_time</span>
                                            <p class="flex text-50 lh-1 mb-0"><small>50 minutes left</small></p>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="material-icons icon-16pt text-50 mr-4pt">play_circle_outline</span>
                                            <p class="flex text-50 lh-1 mb-0"><small>12 lessons</small></p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <a href="student-path.html"
                                            class="btn btn-primary mr-8pt">Resume</a>
                                        <a href="student-path.html"
                                            class="btn btn-outline-secondary ml-0">Start over</a>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <small class="text-50 mr-8pt">Your rating</small>
                                    <div class="rating mr-8pt">
                                        <span class="rating__item"><span class="material-icons text-primary">star</span></span>
                                        <span class="rating__item"><span class="material-icons text-primary">star</span></span>
                                        <span class="rating__item"><span class="material-icons text-primary">star</span></span>
                                        <span class="rating__item"><span class="material-icons text-primary">star</span></span>
                                        <span class="rating__item"><span class="material-icons text-primary">star_border</span></span>
                                    </div>
                                    <small class="text-50">4/5</small>
                                </div>
                            </div>

                        </div>

                        <div class="col-sm-4 card-group-row__col">

                            <div class="card js-overlay card-sm overlay--primary-dodger-blue stack stack--1 card-group-row__card"
                                data-toggle="popover"
                                data-trigger="click">

                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex align-items-center">
                                        <div class="flex">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded mr-12pt z-0 o-hidden">
                                                    <div class="overlay">
                                                        <img src="assets/images/paths/devops_40x40@2x.png"
                                                            width="40"
                                                            height="40"
                                                            alt="Angular"
                                                            class="rounded">
                                                        <span class="overlay__content overlay__content-transparent">
                                                            <span class="overlay__action d-flex flex-column text-center lh-1">
                                                                <small class="h6 small text-white mb-0"
                                                                    style="font-weight: 500;">80%</small>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="flex">
                                                    <div class="card-title">Dev Ops</div>
                                                    <p class="flex text-50 lh-1 mb-0"><small>18 courses</small></p>
                                                </div>
                                            </div>
                                        </div>

                                        <a href="student-path.html"
                                            data-toggle="tooltip"
                                            data-title="Add Favorite"
                                            data-placement="top"
                                            data-boundary="window"
                                            class="ml-4pt material-icons text-20 card-course__icon-favorite">favorite_border</a>

                                    </div>

                                </div>
                            </div>

                            <div class="popoverContainer d-none">
                                <div class="media">
                                    <div class="media-left mr-12pt">
                                        <img src="assets/images/paths/devops_40x40@2x.png"
                                            width="40"
                                            height="40"
                                            alt="Angular"
                                            class="rounded">
                                    </div>
                                    <div class="media-body">
                                        <div class="card-title">Dev Ops</div>
                                        <p class="text-50 d-flex lh-1 mb-0 small">18 courses</p>
                                    </div>
                                </div>

                                <p class="mt-16pt text-70">Learn the fundamentals of working with Dev Ops and how to create basic applications.</p>

                                <div class="my-32pt">
                                    <div class="d-flex align-items-center mb-8pt justify-content-center">
                                        <div class="d-flex align-items-center mr-8pt">
                                            <span class="material-icons icon-16pt text-50 mr-4pt">access_time</span>
                                            <p class="flex text-50 lh-1 mb-0"><small>50 minutes left</small></p>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="material-icons icon-16pt text-50 mr-4pt">play_circle_outline</span>
                                            <p class="flex text-50 lh-1 mb-0"><small>12 lessons</small></p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <a href="student-path.html"
                                            class="btn btn-primary mr-8pt">Resume</a>
                                        <a href="student-path.html"
                                            class="btn btn-outline-secondary ml-0">Start over</a>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <small class="text-50 mr-8pt">Your rating</small>
                                    <div class="rating mr-8pt">
                                        <span class="rating__item"><span class="material-icons text-primary">star</span></span>
                                        <span class="rating__item"><span class="material-icons text-primary">star</span></span>
                                        <span class="rating__item"><span class="material-icons text-primary">star</span></span>
                                        <span class="rating__item"><span class="material-icons text-primary">star</span></span>
                                        <span class="rating__item"><span class="material-icons text-primary">star_border</span></span>
                                    </div>
                                    <small class="text-50">4/5</small>
                                </div>
                            </div>

                        </div>

                        <div class="col-sm-4 card-group-row__col">

                            <div class="card js-overlay card-sm overlay--primary-dodger-blue stack stack--1 card-group-row__card"
                                data-toggle="popover"
                                data-trigger="click">

                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex align-items-center">
                                        <div class="flex">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded mr-12pt z-0 o-hidden">
                                                    <div class="overlay">
                                                        <img src="assets/images/paths/redis_40x40@2x.png"
                                                            width="40"
                                                            height="40"
                                                            alt="Angular"
                                                            class="rounded">
                                                        <span class="overlay__content overlay__content-transparent">
                                                            <span class="overlay__action d-flex flex-column text-center lh-1">
                                                                <small class="h6 small text-white mb-0"
                                                                    style="font-weight: 500;">80%</small>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="flex">
                                                    <div class="card-title">Redis</div>
                                                    <p class="flex text-50 lh-1 mb-0"><small>18 courses</small></p>
                                                </div>
                                            </div>
                                        </div>

                                        <a href="student-path.html"
                                            data-toggle="tooltip"
                                            data-title="Add Favorite"
                                            data-placement="top"
                                            data-boundary="window"
                                            class="ml-4pt material-icons text-20 card-course__icon-favorite">favorite_border</a>

                                    </div>

                                </div>
                            </div>

                            <div class="popoverContainer d-none">
                                <div class="media">
                                    <div class="media-left mr-12pt">
                                        <img src="assets/images/paths/redis_40x40@2x.png"
                                            width="40"
                                            height="40"
                                            alt="Angular"
                                            class="rounded">
                                    </div>
                                    <div class="media-body">
                                        <div class="card-title">Redis</div>
                                        <p class="text-50 d-flex lh-1 mb-0 small">18 courses</p>
                                    </div>
                                </div>

                                <p class="mt-16pt text-70">Learn the fundamentals of working with Redis and how to create basic applications.</p>

                                <div class="my-32pt">
                                    <div class="d-flex align-items-center mb-8pt justify-content-center">
                                        <div class="d-flex align-items-center mr-8pt">
                                            <span class="material-icons icon-16pt text-50 mr-4pt">access_time</span>
                                            <p class="flex text-50 lh-1 mb-0"><small>50 minutes left</small></p>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="material-icons icon-16pt text-50 mr-4pt">play_circle_outline</span>
                                            <p class="flex text-50 lh-1 mb-0"><small>12 lessons</small></p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <a href="student-path.html"
                                            class="btn btn-primary mr-8pt">Resume</a>
                                        <a href="student-path.html"
                                            class="btn btn-outline-secondary ml-0">Start over</a>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <small class="text-50 mr-8pt">Your rating</small>
                                    <div class="rating mr-8pt">
                                        <span class="rating__item"><span class="material-icons text-primary">star</span></span>
                                        <span class="rating__item"><span class="material-icons text-primary">star</span></span>
                                        <span class="rating__item"><span class="material-icons text-primary">star</span></span>
                                        <span class="rating__item"><span class="material-icons text-primary">star</span></span>
                                        <span class="rating__item"><span class="material-icons text-primary">star_border</span></span>
                                    </div>
                                    <small class="text-50">4/5</small>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="row card-group-row mb-lg-8pt">

                        <div class="col-sm-4 card-group-row__col">

                            <div class="card js-overlay card-sm overlay--primary-dodger-blue stack stack--1 card-group-row__card mb-lg-0"
                                data-toggle="popover"
                                data-trigger="click">

                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex align-items-center">
                                        <div class="flex">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded mr-12pt z-0 o-hidden">
                                                    <div class="overlay">
                                                        <img src="assets/images/paths/mailchimp_40x40@2x.png"
                                                            width="40"
                                                            height="40"
                                                            alt="Angular"
                                                            class="rounded">
                                                        <span class="overlay__content overlay__content-transparent">
                                                            <span class="overlay__action d-flex flex-column text-center lh-1">
                                                                <small class="h6 small text-white mb-0"
                                                                    style="font-weight: 500;">80%</small>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="flex">
                                                    <div class="card-title">MailChimp</div>
                                                    <p class="flex text-50 lh-1 mb-0"><small>18 courses</small></p>
                                                </div>
                                            </div>
                                        </div>

                                        <a href="student-path.html"
                                            data-toggle="tooltip"
                                            data-title="Add Favorite"
                                            data-placement="top"
                                            data-boundary="window"
                                            class="ml-4pt material-icons text-20 card-course__icon-favorite">favorite_border</a>

                                    </div>

                                </div>
                            </div>

                            <div class="popoverContainer d-none">
                                <div class="media">
                                    <div class="media-left mr-12pt">
                                        <img src="assets/images/paths/mailchimp_40x40@2x.png"
                                            width="40"
                                            height="40"
                                            alt="Angular"
                                            class="rounded">
                                    </div>
                                    <div class="media-body">
                                        <div class="card-title">MailChimp</div>
                                        <p class="text-50 d-flex lh-1 mb-0 small">18 courses</p>
                                    </div>
                                </div>

                                <p class="mt-16pt text-70">Learn the fundamentals of working with MailChimp and how to create basic applications.</p>

                                <div class="my-32pt">
                                    <div class="d-flex align-items-center mb-8pt justify-content-center">
                                        <div class="d-flex align-items-center mr-8pt">
                                            <span class="material-icons icon-16pt text-50 mr-4pt">access_time</span>
                                            <p class="flex text-50 lh-1 mb-0"><small>50 minutes left</small></p>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="material-icons icon-16pt text-50 mr-4pt">play_circle_outline</span>
                                            <p class="flex text-50 lh-1 mb-0"><small>12 lessons</small></p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <a href="student-path.html"
                                            class="btn btn-primary mr-8pt">Resume</a>
                                        <a href="student-path.html"
                                            class="btn btn-outline-secondary ml-0">Start over</a>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <small class="text-50 mr-8pt">Your rating</small>
                                    <div class="rating mr-8pt">
                                        <span class="rating__item"><span class="material-icons text-primary">star</span></span>
                                        <span class="rating__item"><span class="material-icons text-primary">star</span></span>
                                        <span class="rating__item"><span class="material-icons text-primary">star</span></span>
                                        <span class="rating__item"><span class="material-icons text-primary">star</span></span>
                                        <span class="rating__item"><span class="material-icons text-primary">star_border</span></span>
                                    </div>
                                    <small class="text-50">4/5</small>
                                </div>
                            </div>

                        </div>

                        <div class="col-sm-4 card-group-row__col">

                            <div class="card js-overlay card-sm overlay--primary-dodger-blue stack stack--1 card-group-row__card mb-lg-0"
                                data-toggle="popover"
                                data-trigger="click">

                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex align-items-center">
                                        <div class="flex">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded mr-12pt z-0 o-hidden">
                                                    <div class="overlay">
                                                        <img src="assets/images/paths/swift_40x40@2x.png"
                                                            width="40"
                                                            height="40"
                                                            alt="Angular"
                                                            class="rounded">
                                                        <span class="overlay__content overlay__content-transparent">
                                                            <span class="overlay__action d-flex flex-column text-center lh-1">
                                                                <small class="h6 small text-white mb-0"
                                                                    style="font-weight: 500;">80%</small>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="flex">
                                                    <div class="card-title">Swift</div>
                                                    <p class="flex text-50 lh-1 mb-0"><small>18 courses</small></p>
                                                </div>
                                            </div>
                                        </div>

                                        <a href="student-path.html"
                                            data-toggle="tooltip"
                                            data-title="Add Favorite"
                                            data-placement="top"
                                            data-boundary="window"
                                            class="ml-4pt material-icons text-20 card-course__icon-favorite">favorite_border</a>

                                    </div>

                                </div>
                            </div>

                            <div class="popoverContainer d-none">
                                <div class="media">
                                    <div class="media-left mr-12pt">
                                        <img src="assets/images/paths/swift_40x40@2x.png"
                                            width="40"
                                            height="40"
                                            alt="Angular"
                                            class="rounded">
                                    </div>
                                    <div class="media-body">
                                        <div class="card-title">Swift</div>
                                        <p class="text-50 d-flex lh-1 mb-0 small">18 courses</p>
                                    </div>
                                </div>

                                <p class="mt-16pt text-70">Learn the fundamentals of working with Swift and how to create basic applications.</p>

                                <div class="my-32pt">
                                    <div class="d-flex align-items-center mb-8pt justify-content-center">
                                        <div class="d-flex align-items-center mr-8pt">
                                            <span class="material-icons icon-16pt text-50 mr-4pt">access_time</span>
                                            <p class="flex text-50 lh-1 mb-0"><small>50 minutes left</small></p>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="material-icons icon-16pt text-50 mr-4pt">play_circle_outline</span>
                                            <p class="flex text-50 lh-1 mb-0"><small>12 lessons</small></p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <a href="student-path.html"
                                            class="btn btn-primary mr-8pt">Resume</a>
                                        <a href="student-path.html"
                                            class="btn btn-outline-secondary ml-0">Start over</a>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <small class="text-50 mr-8pt">Your rating</small>
                                    <div class="rating mr-8pt">
                                        <span class="rating__item"><span class="material-icons text-primary">star</span></span>
                                        <span class="rating__item"><span class="material-icons text-primary">star</span></span>
                                        <span class="rating__item"><span class="material-icons text-primary">star</span></span>
                                        <span class="rating__item"><span class="material-icons text-primary">star</span></span>
                                        <span class="rating__item"><span class="material-icons text-primary">star_border</span></span>
                                    </div>
                                    <small class="text-50">4/5</small>
                                </div>
                            </div>

                        </div>

                        <div class="col-sm-4 card-group-row__col">

                            <div class="card js-overlay card-sm overlay--primary-dodger-blue stack stack--1 card-group-row__card mb-lg-0"
                                data-toggle="popover"
                                data-trigger="click">

                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex align-items-center">
                                        <div class="flex">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded mr-12pt z-0 o-hidden">
                                                    <div class="overlay">
                                                        <img src="assets/images/paths/wordpress_40x40@2x.png"
                                                            width="40"
                                                            height="40"
                                                            alt="Angular"
                                                            class="rounded">
                                                        <span class="overlay__content overlay__content-transparent">
                                                            <span class="overlay__action d-flex flex-column text-center lh-1">
                                                                <small class="h6 small text-white mb-0"
                                                                    style="font-weight: 500;">80%</small>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="flex">
                                                    <div class="card-title">WordPress</div>
                                                    <p class="flex text-50 lh-1 mb-0"><small>18 courses</small></p>
                                                </div>
                                            </div>
                                        </div>

                                        <a href="student-path.html"
                                            data-toggle="tooltip"
                                            data-title="Add Favorite"
                                            data-placement="top"
                                            data-boundary="window"
                                            class="ml-4pt material-icons text-20 card-course__icon-favorite">favorite_border</a>

                                    </div>

                                </div>
                            </div>

                            <div class="popoverContainer d-none">
                                <div class="media">
                                    <div class="media-left mr-12pt">
                                        <img src="assets/images/paths/wordpress_40x40@2x.png"
                                            width="40"
                                            height="40"
                                            alt="Angular"
                                            class="rounded">
                                    </div>
                                    <div class="media-body">
                                        <div class="card-title">WordPress</div>
                                        <p class="text-50 d-flex lh-1 mb-0 small">18 courses</p>
                                    </div>
                                </div>

                                <p class="mt-16pt text-70">Learn the fundamentals of working with WordPress and how to create basic applications.</p>

                                <div class="my-32pt">
                                    <div class="d-flex align-items-center mb-8pt justify-content-center">
                                        <div class="d-flex align-items-center mr-8pt">
                                            <span class="material-icons icon-16pt text-50 mr-4pt">access_time</span>
                                            <p class="flex text-50 lh-1 mb-0"><small>50 minutes left</small></p>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="material-icons icon-16pt text-50 mr-4pt">play_circle_outline</span>
                                            <p class="flex text-50 lh-1 mb-0"><small>12 lessons</small></p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <a href="student-path.html"
                                            class="btn btn-primary mr-8pt">Resume</a>
                                        <a href="student-path.html"
                                            class="btn btn-outline-secondary ml-0">Start over</a>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <small class="text-50 mr-8pt">Your rating</small>
                                    <div class="rating mr-8pt">
                                        <span class="rating__item"><span class="material-icons text-primary">star</span></span>
                                        <span class="rating__item"><span class="material-icons text-primary">star</span></span>
                                        <span class="rating__item"><span class="material-icons text-primary">star</span></span>
                                        <span class="rating__item"><span class="material-icons text-primary">star</span></span>
                                        <span class="rating__item"><span class="material-icons text-primary">star_border</span></span>
                                    </div>
                                    <small class="text-50">4/5</small>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
            </div>

            <div class="page-section border-bottom-2">
                <div class="container page__container">
                    <div class="page-separator">
                        <div class="page-separator__text">Design Courses</div>
                    </div>

                    <div class="row card-group-row">

                        <div class="col-md-6 col-lg-4 col-xl-3 card-group-row__col">

                            <div class="card card-sm card--elevated p-relative o-hidden overlay overlay--primary-dodger-blue js-overlay card-group-row__card"
                                data-toggle="popover"
                                data-trigger="click">

                                <a href="student-course.html"
                                    class="card-img-top js-image"
                                    data-position=""
                                    data-height="140">
                                    <img src="assets/images/paths/sketch_430x168.png"
                                        alt="course">
                                    <span class="overlay__content">
                                        <span class="overlay__action d-flex flex-column text-center">
                                            <i class="material-icons icon-32pt">play_circle_outline</i>
                                            <span class="card-title text-white">Preview</span>
                                        </span>
                                    </span>
                                </a>

                                <div class="card-body flex">
                                    <div class="d-flex">
                                        <div class="flex">
                                            <a class="card-title"
                                                href="student-course.html">Learn Sketch</a>
                                            <small class="text-50 font-weight-bold mb-4pt">Elijah Murray</small>
                                        </div>
                                        <a href="student-course.html"
                                            data-toggle="tooltip"
                                            data-title="Add Favorite"
                                            data-placement="top"
                                            data-boundary="window"
                                            class="ml-4pt material-icons text-20 card-course__icon-favorite">favorite_border</a>
                                    </div>
                                    <div class="d-flex">
                                        <div class="rating flex">
                                            <span class="rating__item"><span class="material-icons">star</span></span>
                                            <span class="rating__item"><span class="material-icons">star</span></span>
                                            <span class="rating__item"><span class="material-icons">star</span></span>
                                            <span class="rating__item"><span class="material-icons">star</span></span>
                                            <span class="rating__item"><span class="material-icons">star_border</span></span>
                                        </div>
                                        <!-- <small class="text-50">6 hours</small> -->
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-between">
                                        <div class="col-auto d-flex align-items-center">
                                            <span class="material-icons icon-16pt text-50 mr-4pt">access_time</span>
                                            <p class="flex text-50 lh-1 mb-0"><small>6 hours</small></p>
                                        </div>
                                        <div class="col-auto d-flex align-items-center">
                                            <span class="material-icons icon-16pt text-50 mr-4pt">play_circle_outline</span>
                                            <p class="flex text-50 lh-1 mb-0"><small>12 lessons</small></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="popoverContainer d-none">
                                <div class="media">
                                    <div class="media-left mr-12pt">
                                        <img src="assets/images/paths/sketch_40x40@2x.png"
                                            width="40"
                                            height="40"
                                            alt="Angular"
                                            class="rounded">
                                    </div>
                                    <div class="media-body">
                                        <div class="card-title mb-0">Learn Sketch</div>
                                        <p class="lh-1 mb-0">
                                            <span class="text-50 small">with</span>
                                            <span class="text-50 small font-weight-bold">Elijah Murray</span>
                                        </p>
                                    </div>
                                </div>

                                <p class="my-16pt text-70">Learn the fundamentals of working with Angular and how to create basic applications.</p>

                                <div class="mb-16pt">
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-50 mr-8pt">check</span>
                                        <p class="flex text-50 lh-1 mb-0"><small>Fundamentals of working with Angular</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-50 mr-8pt">check</span>
                                        <p class="flex text-50 lh-1 mb-0"><small>Create complete Angular applications</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-50 mr-8pt">check</span>
                                        <p class="flex text-50 lh-1 mb-0"><small>Working with the Angular CLI</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-50 mr-8pt">check</span>
                                        <p class="flex text-50 lh-1 mb-0"><small>Understanding Dependency Injection</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-50 mr-8pt">check</span>
                                        <p class="flex text-50 lh-1 mb-0"><small>Testing with Angular</small></p>
                                    </div>
                                </div>

                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="d-flex align-items-center mb-4pt">
                                            <span class="material-icons icon-16pt text-50 mr-4pt">access_time</span>
                                            <p class="flex text-50 lh-1 mb-0"><small>6 hours</small></p>
                                        </div>
                                        <div class="d-flex align-items-center mb-4pt">
                                            <span class="material-icons icon-16pt text-50 mr-4pt">play_circle_outline</span>
                                            <p class="flex text-50 lh-1 mb-0"><small>12 lessons</small></p>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="material-icons icon-16pt text-50 mr-4pt">assessment</span>
                                            <p class="flex text-50 lh-1 mb-0"><small>Beginner</small></p>
                                        </div>
                                    </div>
                                    <div class="col text-right">
                                        <a href="student-course.html"
                                            class="btn btn-primary">Watch trailer</a>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="col-md-6 col-lg-4 col-xl-3 card-group-row__col">

                            <div class="card card-sm card--elevated p-relative o-hidden overlay overlay--primary-dodger-blue js-overlay card-group-row__card"
                                data-toggle="popover"
                                data-trigger="click">

                                <a href="student-course.html"
                                    class="card-img-top js-image"
                                    data-position=""
                                    data-height="140">
                                    <img src="assets/images/paths/flinto_430x168.png"
                                        alt="course">
                                    <span class="overlay__content">
                                        <span class="overlay__action d-flex flex-column text-center">
                                            <i class="material-icons icon-32pt">play_circle_outline</i>
                                            <span class="card-title text-white">Preview</span>
                                        </span>
                                    </span>
                                </a>

                                <div class="card-body flex">
                                    <div class="d-flex">
                                        <div class="flex">
                                            <a class="card-title"
                                                href="student-course.html">Learn Flinto</a>
                                            <small class="text-50 font-weight-bold mb-4pt">Elijah Murray</small>
                                        </div>
                                        <a href="student-course.html"
                                            data-toggle="tooltip"
                                            data-title="Add Favorite"
                                            data-placement="top"
                                            data-boundary="window"
                                            class="ml-4pt material-icons text-20 card-course__icon-favorite">favorite_border</a>
                                    </div>
                                    <div class="d-flex">
                                        <div class="rating flex">
                                            <span class="rating__item"><span class="material-icons">star</span></span>
                                            <span class="rating__item"><span class="material-icons">star</span></span>
                                            <span class="rating__item"><span class="material-icons">star</span></span>
                                            <span class="rating__item"><span class="material-icons">star</span></span>
                                            <span class="rating__item"><span class="material-icons">star_border</span></span>
                                        </div>
                                        <!-- <small class="text-50">6 hours</small> -->
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-between">
                                        <div class="col-auto d-flex align-items-center">
                                            <span class="material-icons icon-16pt text-50 mr-4pt">access_time</span>
                                            <p class="flex text-50 lh-1 mb-0"><small>6 hours</small></p>
                                        </div>
                                        <div class="col-auto d-flex align-items-center">
                                            <span class="material-icons icon-16pt text-50 mr-4pt">play_circle_outline</span>
                                            <p class="flex text-50 lh-1 mb-0"><small>12 lessons</small></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="popoverContainer d-none">
                                <div class="media">
                                    <div class="media-left mr-12pt">
                                        <img src="assets/images/paths/flinto_40x40@2x.png"
                                            width="40"
                                            height="40"
                                            alt="Angular"
                                            class="rounded">
                                    </div>
                                    <div class="media-body">
                                        <div class="card-title mb-0">Learn Flinto</div>
                                        <p class="lh-1 mb-0">
                                            <span class="text-50 small">with</span>
                                            <span class="text-50 small font-weight-bold">Elijah Murray</span>
                                        </p>
                                    </div>
                                </div>

                                <p class="my-16pt text-70">Learn the fundamentals of working with Angular and how to create basic applications.</p>

                                <div class="mb-16pt">
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-50 mr-8pt">check</span>
                                        <p class="flex text-50 lh-1 mb-0"><small>Fundamentals of working with Angular</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-50 mr-8pt">check</span>
                                        <p class="flex text-50 lh-1 mb-0"><small>Create complete Angular applications</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-50 mr-8pt">check</span>
                                        <p class="flex text-50 lh-1 mb-0"><small>Working with the Angular CLI</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-50 mr-8pt">check</span>
                                        <p class="flex text-50 lh-1 mb-0"><small>Understanding Dependency Injection</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-50 mr-8pt">check</span>
                                        <p class="flex text-50 lh-1 mb-0"><small>Testing with Angular</small></p>
                                    </div>
                                </div>

                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="d-flex align-items-center mb-4pt">
                                            <span class="material-icons icon-16pt text-50 mr-4pt">access_time</span>
                                            <p class="flex text-50 lh-1 mb-0"><small>6 hours</small></p>
                                        </div>
                                        <div class="d-flex align-items-center mb-4pt">
                                            <span class="material-icons icon-16pt text-50 mr-4pt">play_circle_outline</span>
                                            <p class="flex text-50 lh-1 mb-0"><small>12 lessons</small></p>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="material-icons icon-16pt text-50 mr-4pt">assessment</span>
                                            <p class="flex text-50 lh-1 mb-0"><small>Beginner</small></p>
                                        </div>
                                    </div>
                                    <div class="col text-right">
                                        <a href="student-course.html"
                                            class="btn btn-primary">Watch trailer</a>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="col-md-6 col-lg-4 col-xl-3 card-group-row__col">

                            <div class="card card-sm card--elevated p-relative o-hidden overlay overlay--primary-dodger-blue js-overlay card-group-row__card"
                                data-toggle="popover"
                                data-trigger="click">

                                <a href="student-course.html"
                                    class="card-img-top js-image"
                                    data-position=""
                                    data-height="140">
                                    <img src="assets/images/paths/photoshop_430x168.png"
                                        alt="course">
                                    <span class="overlay__content">
                                        <span class="overlay__action d-flex flex-column text-center">
                                            <i class="material-icons icon-32pt">play_circle_outline</i>
                                            <span class="card-title text-white">Preview</span>
                                        </span>
                                    </span>
                                </a>

                                <div class="card-body flex">
                                    <div class="d-flex">
                                        <div class="flex">
                                            <a class="card-title"
                                                href="student-course.html">Learn Photoshop</a>
                                            <small class="text-50 font-weight-bold mb-4pt">Elijah Murray</small>
                                        </div>
                                        <a href="student-course.html"
                                            data-toggle="tooltip"
                                            data-title="Add Favorite"
                                            data-placement="top"
                                            data-boundary="window"
                                            class="ml-4pt material-icons text-20 card-course__icon-favorite">favorite_border</a>
                                    </div>
                                    <div class="d-flex">
                                        <div class="rating flex">
                                            <span class="rating__item"><span class="material-icons">star</span></span>
                                            <span class="rating__item"><span class="material-icons">star</span></span>
                                            <span class="rating__item"><span class="material-icons">star</span></span>
                                            <span class="rating__item"><span class="material-icons">star</span></span>
                                            <span class="rating__item"><span class="material-icons">star_border</span></span>
                                        </div>
                                        <!-- <small class="text-50">6 hours</small> -->
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-between">
                                        <div class="col-auto d-flex align-items-center">
                                            <span class="material-icons icon-16pt text-50 mr-4pt">access_time</span>
                                            <p class="flex text-50 lh-1 mb-0"><small>6 hours</small></p>
                                        </div>
                                        <div class="col-auto d-flex align-items-center">
                                            <span class="material-icons icon-16pt text-50 mr-4pt">play_circle_outline</span>
                                            <p class="flex text-50 lh-1 mb-0"><small>12 lessons</small></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="popoverContainer d-none">
                                <div class="media">
                                    <div class="media-left mr-12pt">
                                        <img src="assets/images/paths/photoshop_40x40@2x.png"
                                            width="40"
                                            height="40"
                                            alt="Angular"
                                            class="rounded">
                                    </div>
                                    <div class="media-body">
                                        <div class="card-title mb-0">Learn Photoshop</div>
                                        <p class="lh-1 mb-0">
                                            <span class="text-50 small">with</span>
                                            <span class="text-50 small font-weight-bold">Elijah Murray</span>
                                        </p>
                                    </div>
                                </div>

                                <p class="my-16pt text-70">Learn the fundamentals of working with Angular and how to create basic applications.</p>

                                <div class="mb-16pt">
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-50 mr-8pt">check</span>
                                        <p class="flex text-50 lh-1 mb-0"><small>Fundamentals of working with Angular</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-50 mr-8pt">check</span>
                                        <p class="flex text-50 lh-1 mb-0"><small>Create complete Angular applications</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-50 mr-8pt">check</span>
                                        <p class="flex text-50 lh-1 mb-0"><small>Working with the Angular CLI</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-50 mr-8pt">check</span>
                                        <p class="flex text-50 lh-1 mb-0"><small>Understanding Dependency Injection</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-50 mr-8pt">check</span>
                                        <p class="flex text-50 lh-1 mb-0"><small>Testing with Angular</small></p>
                                    </div>
                                </div>

                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="d-flex align-items-center mb-4pt">
                                            <span class="material-icons icon-16pt text-50 mr-4pt">access_time</span>
                                            <p class="flex text-50 lh-1 mb-0"><small>6 hours</small></p>
                                        </div>
                                        <div class="d-flex align-items-center mb-4pt">
                                            <span class="material-icons icon-16pt text-50 mr-4pt">play_circle_outline</span>
                                            <p class="flex text-50 lh-1 mb-0"><small>12 lessons</small></p>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="material-icons icon-16pt text-50 mr-4pt">assessment</span>
                                            <p class="flex text-50 lh-1 mb-0"><small>Beginner</small></p>
                                        </div>
                                    </div>
                                    <div class="col text-right">
                                        <a href="student-course.html"
                                            class="btn btn-primary">Watch trailer</a>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="col-md-6 col-lg-4 col-xl-3 card-group-row__col">

                            <div class="card card-sm card--elevated p-relative o-hidden overlay overlay--primary-dodger-blue js-overlay card-group-row__card"
                                data-toggle="popover"
                                data-trigger="click">

                                <a href="student-course.html"
                                    class="card-img-top js-image"
                                    data-position=""
                                    data-height="140">
                                    <img src="assets/images/paths/figma_430x168.png"
                                        alt="course">
                                    <span class="overlay__content">
                                        <span class="overlay__action d-flex flex-column text-center">
                                            <i class="material-icons icon-32pt">play_circle_outline</i>
                                            <span class="card-title text-white">Preview</span>
                                        </span>
                                    </span>
                                </a>

                                <div class="card-body flex">
                                    <div class="d-flex">
                                        <div class="flex">
                                            <a class="card-title"
                                                href="student-course.html">Learn Figma</a>
                                            <small class="text-50 font-weight-bold mb-4pt">Elijah Murray</small>
                                        </div>
                                        <a href="student-course.html"
                                            data-toggle="tooltip"
                                            data-title="Add Favorite"
                                            data-placement="top"
                                            data-boundary="window"
                                            class="ml-4pt material-icons text-20 card-course__icon-favorite">favorite_border</a>
                                    </div>
                                    <div class="d-flex">
                                        <div class="rating flex">
                                            <span class="rating__item"><span class="material-icons">star</span></span>
                                            <span class="rating__item"><span class="material-icons">star</span></span>
                                            <span class="rating__item"><span class="material-icons">star</span></span>
                                            <span class="rating__item"><span class="material-icons">star</span></span>
                                            <span class="rating__item"><span class="material-icons">star_border</span></span>
                                        </div>
                                        <!-- <small class="text-50">6 hours</small> -->
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-between">
                                        <div class="col-auto d-flex align-items-center">
                                            <span class="material-icons icon-16pt text-50 mr-4pt">access_time</span>
                                            <p class="flex text-50 lh-1 mb-0"><small>6 hours</small></p>
                                        </div>
                                        <div class="col-auto d-flex align-items-center">
                                            <span class="material-icons icon-16pt text-50 mr-4pt">play_circle_outline</span>
                                            <p class="flex text-50 lh-1 mb-0"><small>12 lessons</small></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="popoverContainer d-none">
                                <div class="media">
                                    <div class="media-left mr-12pt">
                                        <img src="assets/images/paths/figma_40x40@2x.png"
                                            width="40"
                                            height="40"
                                            alt="Angular"
                                            class="rounded">
                                    </div>
                                    <div class="media-body">
                                        <div class="card-title mb-0">Learn Figma</div>
                                        <p class="lh-1 mb-0">
                                            <span class="text-50 small">with</span>
                                            <span class="text-50 small font-weight-bold">Elijah Murray</span>
                                        </p>
                                    </div>
                                </div>

                                <p class="my-16pt text-70">Learn the fundamentals of working with Angular and how to create basic applications.</p>

                                <div class="mb-16pt">
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-50 mr-8pt">check</span>
                                        <p class="flex text-50 lh-1 mb-0"><small>Fundamentals of working with Angular</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-50 mr-8pt">check</span>
                                        <p class="flex text-50 lh-1 mb-0"><small>Create complete Angular applications</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-50 mr-8pt">check</span>
                                        <p class="flex text-50 lh-1 mb-0"><small>Working with the Angular CLI</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-50 mr-8pt">check</span>
                                        <p class="flex text-50 lh-1 mb-0"><small>Understanding Dependency Injection</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-50 mr-8pt">check</span>
                                        <p class="flex text-50 lh-1 mb-0"><small>Testing with Angular</small></p>
                                    </div>
                                </div>

                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="d-flex align-items-center mb-4pt">
                                            <span class="material-icons icon-16pt text-50 mr-4pt">access_time</span>
                                            <p class="flex text-50 lh-1 mb-0"><small>6 hours</small></p>
                                        </div>
                                        <div class="d-flex align-items-center mb-4pt">
                                            <span class="material-icons icon-16pt text-50 mr-4pt">play_circle_outline</span>
                                            <p class="flex text-50 lh-1 mb-0"><small>12 lessons</small></p>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="material-icons icon-16pt text-50 mr-4pt">assessment</span>
                                            <p class="flex text-50 lh-1 mb-0"><small>Beginner</small></p>
                                        </div>
                                    </div>
                                    <div class="col text-right">
                                        <a href="student-course.html"
                                            class="btn btn-primary">Watch trailer</a>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
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
                    <p class="measure-lead-max text-50 small mr-8pt">Luma is a beautifully crafted user interface for modern Education Platforms, including Courses & Tutorials, Video Lessons, Student and Teacher Dashboard, Curriculum Management, Earnings and Reporting, ERP, HR, CMS, Tasks, Projects, eCommerce and more.</p>
                    <p class="mb-8pt d-flex">
                        <a href=""
                            class="text-70 text-underline mr-8pt small">Terms</a>
                        <a href=""
                            class="text-70 text-underline small">Privacy policy</a>
                    </p>
                    <p class="text-50 small mt-n1 mb-0">Copyright 2019 &copy; All rights reserved.</p>
                </div>
            </div>

            <!-- // END Footer -->

        </div>

        <!-- // END drawer-layout__content -->

        <!-- Drawer -->

        <div class="mdk-drawer js-mdk-drawer"
            id="default-drawer">
            <div class="mdk-drawer__content">
                <div class="sidebar sidebar-dark-pickled-bluewood sidebar-left"
                    data-perfect-scrollbar>

                    <!-- Sidebar Content -->

                    <div class="d-flex align-items-center navbar-height">
                        <form action="index.html"
                            class="search-form search-form--black mx-16pt pr-0 pl-16pt">
                            <input type="text"
                                class="form-control pl-0"
                                placeholder="Search">
                            <button class="btn"
                                type="submit"><i class="material-icons">search</i></button>
                        </form>
                    </div>

                    <a href="index.html"
                        class="sidebar-brand ">
                        <!-- <img class="sidebar-brand-icon" src="assets/images/illustration/student/128/white.svg" alt="Luma"> -->

                        <span class="avatar avatar-xl sidebar-brand-icon h-auto">

                            <span class="avatar-title rounded bg-primary"><img src="assets/images/illustration/student/128/white.svg"
                                    class="img-fluid"
                                    alt="logo" /></span>

                        </span>

                        <span>Luma</span>
                    </a>

                    <div class="sidebar-heading">Student</div>
                    <ul class="sidebar-menu">

                        <li class="sidebar-menu-item active">
                            <a class="sidebar-menu-button"
                                href="index.html">
                                <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">home</span>
                                <span class="sidebar-menu-text">Home</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button"
                                href="courses.html">
                                <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">local_library</span>
                                <span class="sidebar-menu-text">Browse Courses</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button"
                                href="paths.html">
                                <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">style</span>
                                <span class="sidebar-menu-text">Browse Paths</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button"
                                href="student-dashboard.html">
                                <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">account_box</span>
                                <span class="sidebar-menu-text">Student Dashboard</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button"
                                href="student-my-courses.html">
                                <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">search</span>
                                <span class="sidebar-menu-text">My Courses</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button"
                                href="student-paths.html">
                                <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">timeline</span>
                                <span class="sidebar-menu-text">My Paths</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button"
                                href="student-path.html">
                                <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">change_history</span>
                                <span class="sidebar-menu-text">Path Details</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button"
                                href="student-course.html">
                                <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">face</span>
                                <span class="sidebar-menu-text">Course Preview</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button"
                                href="student-lesson.html">
                                <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">panorama_fish_eye</span>
                                <span class="sidebar-menu-text">Lesson Preview</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button"
                                href="student-take-course.html">
                                <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">class</span>
                                <span class="sidebar-menu-text">Take Course</span>
                                <span class="sidebar-menu-badge badge badge-accent badge-notifications ml-auto">PRO</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button"
                                href="student-take-lesson.html">
                                <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">import_contacts</span>
                                <span class="sidebar-menu-text">Take Lesson</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button"
                                href="student-take-quiz.html">
                                <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">dvr</span>
                                <span class="sidebar-menu-text">Take Quiz</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button"
                                href="student-quiz-results.html">
                                <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">poll</span>
                                <span class="sidebar-menu-text">My Quizzes</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button"
                                href="student-quiz-result-details.html">
                                <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">live_help</span>
                                <span class="sidebar-menu-text">Quiz Result</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button"
                                href="student-path-assessment.html">
                                <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">layers</span>
                                <span class="sidebar-menu-text">Skill Assessment</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button"
                                href="student-path-assessment-result.html">
                                <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">assignment_turned_in</span>
                                <span class="sidebar-menu-text">Skill Result</span>
                            </a>
                        </li>

                    </ul>


                    <li class="sidebar-menu-item">
                        <a class="sidebar-menu-button js-sidebar-collapse"
                            data-toggle="collapse"
                            href="#enterprise_menu">
                            <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">donut_large</span>
                            Enterprise
                            <span class="ml-auto sidebar-menu-toggle-icon"></span>
                        </a>
                        <ul class="sidebar-submenu collapse sm-indent"
                            id="enterprise_menu">
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="erp-dashboard.html">
                                    <span class="sidebar-menu-text">ERP Dashboard</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="crm-dashboard.html">
                                    <span class="sidebar-menu-text">CRM Dashboard</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="hr-dashboard.html">
                                    <span class="sidebar-menu-text">HR Dashboard</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="employees.html">
                                    <span class="sidebar-menu-text">Employees</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="staff.html">
                                    <span class="sidebar-menu-text">Staff</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="leaves.html">
                                    <span class="sidebar-menu-text">Leaves</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button disabled"
                                    href="departments.html">
                                    <span class="sidebar-menu-text">Departments</span>
                                </a>
                            </li>

                        </ul>
                    </li>
                    <li class="sidebar-menu-item">
                        <a class="sidebar-menu-button"
                            data-toggle="collapse"
                            href="#productivity_menu">
                            <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">access_time</span>
                            Productivity
                            <span class="ml-auto sidebar-menu-toggle-icon"></span>
                        </a>
                        <ul class="sidebar-submenu collapse sm-indent"
                            id="productivity_menu">
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="projects.html">
                                    <span class="sidebar-menu-text">Projects</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="tasks-board.html">
                                    <span class="sidebar-menu-text">Tasks Board</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="tasks-list.html">
                                    <span class="sidebar-menu-text">Tasks List</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button disabled"
                                    href="kanban.html">
                                    <span class="sidebar-menu-text">Kanban</span>
                                </a>
                            </li>

                        </ul>
                    </li>
                    <li class="sidebar-menu-item">
                        <a class="sidebar-menu-button"
                            data-toggle="collapse"
                            href="#ecommerce_menu">
                            <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">shopping_cart</span>
                            eCommerce
                            <span class="ml-auto sidebar-menu-toggle-icon"></span>
                        </a>
                        <ul class="sidebar-submenu collapse sm-indent"
                            id="ecommerce_menu">
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="ecommerce.html">
                                    <span class="sidebar-menu-text">Shop Dashboard</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button disabled"
                                    href="edit-product.html">
                                    <span class="sidebar-menu-text">Edit Product</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-menu-item">
                        <a class="sidebar-menu-button"
                            data-toggle="collapse"
                            href="#messaging_menu">
                            <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">message</span>
                            Messaging
                            <span class="sidebar-menu-badge badge badge-accent badge-notifications ml-auto">2</span>
                            <span class="sidebar-menu-toggle-icon"></span>
                        </a>
                        <ul class="sidebar-submenu collapse sm-indent"
                            id="messaging_menu">
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="messages.html">
                                    <span class="sidebar-menu-text">Messages</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="email.html">
                                    <span class="sidebar-menu-text">Email</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-menu-item">
                        <a class="sidebar-menu-button"
                            data-toggle="collapse"
                            href="#cms_menu">
                            <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">content_copy</span>
                            CMS
                            <span class="ml-auto sidebar-menu-toggle-icon"></span>
                        </a>
                        <ul class="sidebar-submenu collapse sm-indent"
                            id="cms_menu">
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="cms-dashboard.html">
                                    <span class="sidebar-menu-text">CMS Dashboard</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="posts.html">
                                    <span class="sidebar-menu-text">Posts</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-menu-item">
                        <a class="sidebar-menu-button"
                            data-toggle="collapse"
                            href="#account_menu">
                            <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">account_box</span>
                            Account
                            <span class="ml-auto sidebar-menu-toggle-icon"></span>
                        </a>
                        <ul class="sidebar-submenu collapse sm-indent"
                            id="account_menu">
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="pricing.html">
                                    <span class="sidebar-menu-text">Pricing</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="login.html">
                                    <span class="sidebar-menu-text">Login</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="signup.html">
                                    <span class="sidebar-menu-text">Signup</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="signup-payment.html">
                                    <span class="sidebar-menu-text">Payment</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="reset-password.html">
                                    <span class="sidebar-menu-text">Reset Password</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="change-password.html">
                                    <span class="sidebar-menu-text">Change Password</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="edit-account.html">
                                    <span class="sidebar-menu-text">Edit Account</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="edit-account-profile.html">
                                    <span class="sidebar-menu-text">Profile &amp; Privacy</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="edit-account-notifications.html">
                                    <span class="sidebar-menu-text">Email Notifications</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="edit-account-password.html">
                                    <span class="sidebar-menu-text">Account Password</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="billing.html">
                                    <span class="sidebar-menu-text">Subscription</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="billing-upgrade.html">
                                    <span class="sidebar-menu-text">Upgrade Account</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="billing-payment.html">
                                    <span class="sidebar-menu-text">Payment Information</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="billing-history.html">
                                    <span class="sidebar-menu-text">Payment History</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="billing-invoice.html">
                                    <span class="sidebar-menu-text">Invoice</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-menu-item">
                        <a class="sidebar-menu-button"
                            data-toggle="collapse"
                            href="#community_menu">
                            <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">people_outline</span>
                            Community
                            <span class="ml-auto sidebar-menu-toggle-icon"></span>
                        </a>
                        <ul class="sidebar-submenu collapse sm-indent"
                            id="community_menu">
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="teachers.html">

                                    <span class="sidebar-menu-text">Browse Teachers</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="student-profile.html">

                                    <span class="sidebar-menu-text">Student Profile</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="teacher-profile.html">

                                    <span class="sidebar-menu-text">Teacher Profile</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="blog.html">

                                    <span class="sidebar-menu-text">Blog</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="blog-post.html">

                                    <span class="sidebar-menu-text">Blog Post</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="faq.html">
                                    <span class="sidebar-menu-text">FAQ</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="help-center.html">
                                    <!--  -->
                                    <span class="sidebar-menu-text">Help Center</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="discussions.html">
                                    <span class="sidebar-menu-text">Discussions</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="discussion.html">
                                    <span class="sidebar-menu-text">Discussion Details</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button"
                                    href="discussions-ask.html">
                                    <span class="sidebar-menu-text">Ask Question</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    </ul>


                    </ul>
                    </li>
                    </ul>

                    <!-- // END Sidebar Content -->

                </div>
            </div>
        </div>

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