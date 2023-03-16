<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle = Yii::app()->name . ' - Login';
$this->breadcrumbs = array(
	'Login',
);
?>
<h1>Iniciar sesión</h1>
<div class="from">
	<div class="col-lg-4 offset-lg-4 col-md-4 offset-md-4 wow fadeInUp" data-wow-delay="0.5s">
		<?php $form = $this->beginWidget('CActiveForm', array(
			'id' => 'login-form',
			'enableClientValidation' => true,
			'clientOptions' => array(
				'validateOnChange' => true,
				'validateOnSubmit' => true,
			),
		)); ?>
		<div class="row g-3">
			<div class="col-md-12">
				<div class="form-floating">
					<?php echo $form->textField($model, 'username', ['class' => 'form-control', 'placeholder' => 'Usuario']); ?>
					<?php echo $form->labelEx($model, 'username'); ?>
					<?php echo $form->error($model, 'username'); ?>
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-floating">
					<?php echo $form->passwordField($model, 'password', ['class' => 'form-control', 'placeholder' => 'Contraseña']); ?>
					<?php echo $form->labelEx($model, 'password'); ?>
					<?php echo $form->error($model, 'password'); ?>
				</div>
			</div>
			<div class="col-md-12 rememberMe">
				<?php echo $form->checkBox($model, 'rememberMe'); ?>
				<?php echo $form->label($model, 'rememberMe'); ?>
				<?php echo $form->error($model, 'rememberMe'); ?>
			</div>
			<div class="col-md-6 offset-md-3">
				<?php echo CHtml::submitButton('Ingresar', ['class' => 'btn btn-primary w-100 py-3']); ?>
			</div>
		</div>
		<?php $this->endWidget(); ?>
	</div>
</div>