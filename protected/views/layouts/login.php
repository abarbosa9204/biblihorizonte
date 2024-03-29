<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Biblihorizonte</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="<?php echo Yii::app()->baseUrl; ?>/themes/elearning-1.0.0/img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="<?php echo Yii::app()->baseUrl; ?>/themes/elearning-1.0.0/lib/animate/animate.min.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->baseUrl; ?>/themes/elearning-1.0.0/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="<?php echo Yii::app()->baseUrl; ?>/themes/elearning-1.0.0/css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="<?php echo Yii::app()->baseUrl; ?>/themes/elearning-1.0.0/css/style.css" rel="stylesheet">
    <!-- Custom Stylesheet -->
    <link href="<?php echo Yii::app()->baseUrl; ?>/themes/elearning-1.0.0/css/styleDefault.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->


    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-dark navbar-light shadow sticky-top p-0">
        <a href="index.html" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <img class="img-fluid p-1" src="<?php echo Yii::app()->baseUrl; ?>/images/Fundacion_Universitaria_Horizonte.png" alt="Unihorizonte" style="height: 45px;">
            <!-- <h2 class="m-0 text-primary"><i class="fa fa-book me-3"></i>< ?php echo CHtml::encode(Yii::app()->name); ?></h2> -->
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="<?php echo Yii::app()->baseUrl; ?>/site/index" class="nav-item nav-link">Inicio</a>
                <a href="<?php echo Yii::app()->baseUrl; ?>/site/login" class="nav-item nav-link active">Login</a>
                <!-- <a href="<.?php echo Yii::app()->baseUrl; ?>/site/register" class="nav-item nav-link">Registrarse</a> -->
            </div>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- Team Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <?php echo $content; ?>
            </div>
        </div>
    </div>

    <?php $this->renderPartial('/layouts/_footer') ?>


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <!-- <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo Yii::app()->baseUrl; ?>/themes/elearning-1.0.0/lib/wow/wow.min.js"></script>
    <script src="<?php echo Yii::app()->baseUrl; ?>/themes/elearning-1.0.0/lib/easing/easing.min.js"></script>
    <script src="<?php echo Yii::app()->baseUrl; ?>/themes/elearning-1.0.0/lib/waypoints/waypoints.min.js"></script>
    <script src="<?php echo Yii::app()->baseUrl; ?>/themes/elearning-1.0.0/lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="<?php echo Yii::app()->baseUrl; ?>/themes/elearning-1.0.0/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            toastr.options = {
                closeButton: true,
                debug: false,
                newestOnTop: false,
                progressBar: false,
                positionClass: "toast-bottom-center",
                preventDuplicates: false,
                onclick: null,
                showDuration: "300",
                hideDuration: "1000",
                timeOut: "5000",
                extendedTimeOut: "1000",
                showEasing: "swing",
                hideEasing: "linear",
                showMethod: "fadeIn",
                hideMethod: "fadeOut",
            };
        });
    </script>
</body>

</html>