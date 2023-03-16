<div class="modal fade" id="show-text-bookModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="show-text-book-ModalLabel">TEXTO</h5>
                <button type="button" class="btn-close" onclick="closeModalText()"></button>
            </div>
            <div class="modal-body">
                <p class="text-description-book"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModalText()">CERRAR</button>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/dist/js-views/books/_show-text-book.js"></script>