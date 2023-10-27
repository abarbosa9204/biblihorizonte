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
					<?php echo $form->error($model, 'username', ['class' => 'invalid-feedback']); ?>
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-floating">
					<?php echo $form->passwordField($model, 'password', ['class' => 'form-control', 'placeholder' => 'Contraseña']); ?>
					<?php echo $form->labelEx($model, 'password'); ?>
					<?php echo $form->error($model, 'password', ['class' => 'invalid-feedback']); ?>
				</div>
			</div>
			<a class="" href="javascript:void(0);" onclick="passwordRecovery()" title="Recuperar contraseña"><i class="ti-lock global-font-type-20 pl-1 pr-1" style="color:#B61020;"></i>Recuperar contraseña</a>
			<div class="col-md-12 rememberMe">
				<?php echo $form->checkBox($model, 'rememberMe'); ?>
				<?php echo $form->label($model, 'rememberMe'); ?>
				<?php echo $form->error($model, 'rememberMe', ['class' => 'invalid-feedback']); ?>
			</div>
			<a href="" class="hre"></a>
			<div class="col-md-6 offset-md-3">
				<?php echo CHtml::submitButton('Ingresar', ['class' => 'btn btn-primary w-100 py-3']); ?>
			</div>
		</div>
		<?php $this->endWidget(); ?>
	</div>
</div>

<div class="modal fade" id="password-recovery" tabindex="-1" aria-labelledby="password-recoveryLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="password-recoveryLabel">Recuperar contrseña</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="form-password-recovery" name="form-password-recovery">
					<div class="form-floating mb-3 text-start">
						<input type="email" class="form-control" id="email-recovery-pass" name="email-recovery-pass" placeholder="email@unihorizonte.edu.co" required>
						<label for="email-recovery-pass" class="col-form-label">Email institucional</label>
						<span id="response-pass"></span>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-primary" onclick="submitPasswordRecovery()">Enviar</button>
			</div>
		</div>
	</div>
</div>

<script>
	function passwordRecovery() {
		$('#password-recovery').modal('show');
		$('#response-pass').html('');
	}

	function submitPasswordRecovery() {
		$.ajax({
			url: "PasswordRecovery",
			type: "post",
			dataType: "json",
			data: {
				email: $('#email-recovery-pass').val(),
			},
			success: function(data) {
				if (data['Status'] == 200) {
					toastr.success(data.Message);
					$("#password-recovery").modal("hide").data("bs.modal", null);
				} else if (data['Status'] == 400) {
					toastr.warning(data.Message);
					$('#response-pass').html(data.Message);
				}
			},
			error: function(data) {
				toastr.error('Error procesando la solicitud, por favor comunicarse con el administrador')
			}
		});
	}
</script>