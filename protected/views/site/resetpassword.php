<?php
/* @var $this SiteController */
/* @var $model resetForm */
/* @var $form CActiveForm  */

$this->pageTitle = Yii::app()->name . ' - Nueva contraseña';
$this->breadcrumbs = array(
    'Nueva contraseña',
);
?>
<h1>Actualizar contraeña</h1>
<div class="from">
    <div class="col-lg-4 offset-lg-4 col-md-4 offset-md-4 wow fadeInUp" data-wow-delay="0.5s">
        <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'reset-login-form',
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnChange' => true,
                'validateOnSubmit' => true,
            ),
        )); ?>
        <div class="row g-3">
            <div class="row">
                <div class="col-md-12">
                    <?php $unifiedErrors = $model->getUnifiedErrors(); ?>
                    <?php if (!empty($unifiedErrors)) : ?>
                        <div class="errorSummary text-danger">
                            Errores a solucionar:
                            <?php foreach ($unifiedErrors as $error) : ?>
                                <br><i class="fas fa-times error-icon"></i> <?php echo $error; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-floating">
                    <?php echo $form->passwordField($model, 'currentpassword', ['class' => 'form-control', 'placeholder' => 'Contraseña']); ?>
                    <?php echo $form->labelEx($model, 'currentpassword'); ?>
                    <?php echo $form->error($model, 'currentpassword', ['class' => 'invalid-feedback']); ?>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-floating">
                    <?php echo $form->passwordField($model, 'newpassword', ['class' => 'form-control', 'placeholder' => 'Contraseña']); ?>
                    <?php echo $form->labelEx($model, 'newpassword'); ?>
                    <?php echo $form->error($model, 'newpassword', ['class' => 'invalid-feedback']); ?>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-floating">
                    <?php echo $form->passwordField($model, 'confirmpassword', ['class' => 'form-control', 'placeholder' => 'Contraseña']); ?>
                    <?php echo $form->labelEx($model, 'confirmpassword'); ?>
                    <?php echo $form->error($model, 'confirmpassword', ['class' => 'invalid-feedback']); ?>
                </div>
            </div>
            <a href="" class="hre"></a>
            <div class="col-md-6 offset-md-3">
                <?php echo CHtml::submitButton('Actualizar', ['class' => 'btn btn-primary w-100 py-3']); ?>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>