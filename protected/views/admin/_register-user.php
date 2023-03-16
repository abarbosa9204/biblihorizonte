<!-- Modal -->
<div class="modal fade" id="usersModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="usersModalLabel">REGISTRAR USUARIO</h5>
                <button type="button" class="btn-close" onclick="resetFormCreateUser('cancel')"></button>
            </div>
            <div class="modal-body">
                <form action="#" method="POST" id="form-create-user" name="form-create-user">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control validate-text" id="create-user-person-id" name="create-user-person-id" placeholder="Cédula">
                                <label for="create-user-person-id">Cédula</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control validate-text" id="create-user-name" name="create-user-name" placeholder="Nombres" maxlength="100">
                                <label for="create-user-name">Nombres</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control validate-text" id="create-user-surname" name="create-user-surname" placeholder="Apellidos" maxlength="100">
                                <label for="create-user-surname">Apellidos</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" class="form-control validate-text" id="create-user-email" name="create-user-email" placeholder="Correo" maxlength="40">
                                <label for="create-user-email">Correo</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control validate-number" id="create-user-phone" name="create-user-phone" placeholder="Teléfono" maxlength="13">
                                <label for="create-user-phone">Telfono</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <?= CHtml::dropDownList(
                                    'create-user-program',
                                    '',
                                    CHtml::listData(Tbl_programas::model()
                                        ->findAll(
                                            'Estado=:activate',
                                            array(':activate' => 1),
                                            array('order' => 'Programa')
                                        ), 'RowId', 'Programa'),
                                    array('prompt' => '-Seleccione-', 'class' => 'form-control bg-white', 'aria-describedby' => 'madeByHelp')
                                ); ?>
                                <label for="create-user-program">Programa</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <?= CHtml::dropDownList(
                                    'create-user-role',
                                    '',
                                    CHtml::listData(Tbl_role::model()
                                        ->findAll(
                                            'Estado=:activate',
                                            array(':activate' => 1),
                                            array('order' => 'Nombre')
                                        ), 'RowId', 'Nombre'),
                                    array('prompt' => '-Seleccione-', 'class' => 'form-control bg-white', 'aria-describedby' => 'madeByHelp')
                                ); ?>
                                <label for="create-user-role">Role</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="password" class="form-control" id="create-user-password" name="create-user-password" placeholder="Contraseña" minlength="8">
                                <label for="create-user-password">Contraseña</label>
                                <span id="create-user-password-error"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="resetFormCreateUser('cancel')">CANCELAR</button>
                <button type="button" class="btn btn-primary" onclick="submitFormCreateUser()">CREAR</button>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/dist/js-views/admin/_register-user.js"></script>