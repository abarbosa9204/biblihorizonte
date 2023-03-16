<!-- Modal -->
<div class="modal fade" id="edit-usersModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-usersModalLabel">EDITAR USUARIO</h5>
                <button type="button" class="btn-close" onclick="resetFormEditUser('cancel')"></button>
            </div>
            <div class="modal-body">
                <form action="#" method="POST" id="form-edit-user" name="form-edit-user">
                    <input type="hidden" value="" id="id-user-edit" name="id-user-edit" readonly>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control validate-text" id="edit-user-person-id" name="edit-user-person-id" placeholder="Cédula" readonly>
                                <label for="edit-user-person-id">Cédula</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control validate-text" id="edit-user-name" name="edit-user-name" placeholder="Nombres" maxlength="100">
                                <label for="edit-user-name">Nombres</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control validate-text" id="edit-user-surname" name="edit-user-surname" placeholder="Apellidos" maxlength="100">
                                <label for="edit-user-surname">Apellidos</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" class="form-control validate-text" id="edit-user-email" name="edit-user-email" placeholder="Correo" maxlength="40">
                                <label for="edit-user-email">Correo</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control validate-number" id="edit-user-phone" name="edit-user-phone" placeholder="Teléfono" maxlength="13">
                                <label for="edit-user-phone">Telfono</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <?= CHtml::dropDownList(
                                    'edit-user-program',
                                    '',
                                    CHtml::listData(Tbl_programas::model()
                                        ->findAll(
                                            'Estado=:activate',
                                            array(':activate' => 1),
                                            array('order' => 'Programa')
                                        ), 'RowId', 'Programa'),
                                    array('prompt' => '-Seleccione-', 'class' => 'form-control bg-white', 'aria-describedby' => 'madeByHelp')
                                ); ?>
                                <label for="edit-user-program">Programa</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <?= CHtml::dropDownList(
                                    'edit-user-role',
                                    '',
                                    CHtml::listData(Tbl_role::model()
                                        ->findAll(
                                            'Estado=:activate',
                                            array(':activate' => 1),
                                            array('order' => 'Nombre')
                                        ), 'RowId', 'Nombre'),
                                    array('prompt' => '-Seleccione-', 'class' => 'form-control bg-white', 'aria-describedby' => 'madeByHelp')
                                ); ?>
                                <label for="edit-user-role">Role</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="password" class="form-control" id="edit-user-password" name="edit-user-password" placeholder="Contraseña" minlength="8">
                                <label for="edit-user-password">Contraseña</label>
                                <span id="edit-user-password-error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <?= CHtml::dropDownList('edit-user-status', '', ['0' => 'Bloqueado', '1' => 'Activo'], array('size' => 1, 'class' => 'form-control bg-white', 'aria-describedby' => 'madeByHelp')); ?>
                                <label for="edit-user-status">Estado</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="edit-user-fcreacion" name="edit-user-fcreacion" readonly>
                                <label for="edit-user-fcreacion">Fecha de creación</label>                                
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="resetFormEditUser('cancel')">CANCELAR</button>
                <button type="button" class="btn btn-primary" onclick="submitFormEditUser()">EDITAR</button>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/dist/js-views/admin/_edit-user.js"></script>