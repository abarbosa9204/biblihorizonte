<div class="modal fade" id="reserv-book-modal" tabindex="-1" aria-labelledby="reserv-book-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reserv-book-modal-label">Formulario de Préstamo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="resetFormReservBook('reset')"></button>
            </div>
            <div class="modal-body">
                <form id="form-reserv-book" name="form-reserv-book">
                    <input type="hidden" value="" name="row-id-book-reserv" id="row-id-book-reserv">
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control validate-text blocked-field" id="reserv-name" name="reserv-name" placeholder="Nombre completo" maxlength="120" required>
                            <label for="reserv-name">Nombre Completo</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control validate-text blocked-field" id="reserv-dni" name="reserv-dni" placeholder="" maxlength="120" required>
                            <label for="reserv-dni">Cédula</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control validate-text blocked-field" id="reserv-email" name="reserv-email" placeholder="" maxlength="120" required>
                            <label for="reserv-email">Email</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control validate-number" id="reserv-phone" name="reserv-phone" placeholder="" maxlength="10" required>
                            <label for="reserv-phone">Celular</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="date" class="form-control pointer" id="reserv-date-init" name="reserv-date-init">
                            <label for="reserv-date-init" class="form-label">Fecha de Inicio de Préstamo</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="date" class="form-control pointer" id="reserv-date-end" name="reserv-date-end">
                            <label for="reserv-date-end" class="form-label">Fecha de Entrega de Préstamo</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="resetFormReservBook('reset')">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="SaveFormReservBook()">Guardar</button>                
            </div>
        </div>
    </div>
</div>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/dist/js-views/books/_reserv-book.js"></script>