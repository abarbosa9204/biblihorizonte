<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?php echo CHtml::encode(Yii::app()->name); ?></title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="<?php echo Yii::app()->baseUrl; ?>/themes/elearning-1.0.0/img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">
    <!-- Datatables -->
    <link href="<?php echo Yii::app()->baseUrl; ?>/dist/DataTables/datatables.min.css" rel="stylesheet" />
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="<?php echo Yii::app()->baseUrl; ?>/themes/elearning-1.0.0/lib/animate/animate.min.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->baseUrl; ?>/themes/elearning-1.0.0/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="<?php echo Yii::app()->baseUrl; ?>/themes/elearning-1.0.0/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <!-- themify-icons -->
    <link href="<?php echo Yii::app()->baseUrl; ?>/css/themify-icons.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="<?php echo Yii::app()->baseUrl; ?>/themes/elearning-1.0.0/css/style.css" rel="stylesheet">
    <!-- Custom Stylesheet -->
    <link href="<?php echo Yii::app()->baseUrl; ?>/themes/elearning-1.0.0/css/styleDefault.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <?php $this->renderPartial('/layouts/_header') ?>

    <?php echo $content; ?>

    <?php $this->renderPartial('/layouts/_footer') ?>

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo Yii::app()->baseUrl; ?>/themes/elearning-1.0.0/lib/wow/wow.min.js"></script>
    <script src="<?php echo Yii::app()->baseUrl; ?>/themes/elearning-1.0.0/lib/easing/easing.min.js"></script>
    <script src="<?php echo Yii::app()->baseUrl; ?>/themes/elearning-1.0.0/lib/waypoints/waypoints.min.js"></script>
    <script src="<?php echo Yii::app()->baseUrl; ?>/themes/elearning-1.0.0/lib/owlcarousel/owl.carousel.min.js"></script>

    <script src="<?php echo Yii::app()->baseUrl; ?>/dist/DataTables/datatables.min.js"></script>
    <!-- Custom.js -->
    <script src="<?php echo Yii::app()->baseUrl; ?>/dist/js/custom.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Template Javascript -->
    <script src="<?php echo Yii::app()->baseUrl; ?>/themes/elearning-1.0.0/js/main.js"></script>
    <script>
        function runEveryHour() {
            const now = new Date();
            const executionTime = new Date(now);
            executionTime.setHours(executionTime.getHours() + 1);

            const waitTime = executionTime - now;
            //const waitTime = 10000;

            setTimeout(function() {
                runEveryHour();
                $.ajax({
                    url: '../BooksReservation/UpdateReservations',
                    method: 'POST',
                    dataType: "json",
                    success: function(response) {
                        console.log(response)
                    }
                });
            }, waitTime);
        }
        runEveryHour();
    </script>
</body>

</html>