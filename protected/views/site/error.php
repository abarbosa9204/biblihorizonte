<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle = Yii::app()->name . ' - Error';
$this->breadcrumbs = array(
	'Error',
);
?>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<!-- Header Start -->
<div class="container-fluid bg-primary py-5 mb-5 page-header">
	<div class="container py-5">
		<div class="row justify-content-center">
			<div class="col-lg-10 text-center">
			<h1 class="display-3 text-white animated slideInDown">Not Found</h1>
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb justify-content-center">
						<li class="breadcrumb-item"><a class="text-white" href="#">Inicio</a></li>						
						<li class="breadcrumb-item text-white active" aria-current="page"><?php echo $code; ?></li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
</div>
<!-- Header End -->
<!-- 404 Start -->
<div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
	<div class="container text-center">
		<div class="row justify-content-center">
			<div class="col-lg-6">
				<i class="bi bi-exclamation-triangle display-1 text-primary"></i>
				<h1 class="display-1"><?php echo $code; ?></h1>
				<h1 class="mb-4">Page Not Found</h1>
				<p class="mb-4"><?php echo CHtml::encode($message); ?></p>
				<a class="btn btn-primary rounded-pill py-3 px-5" href="<?php echo Yii::app()->baseUrl; ?>/site/index">Volver al inicio</a>
			</div>
		</div>
	</div>
</div>