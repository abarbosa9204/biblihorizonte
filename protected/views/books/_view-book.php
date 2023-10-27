<!-- Modal -->
<div class="modal fade" id="view-booksModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="view-booksModalLabel">Información detallada</h5>
                <button type="button" class="btn-close" onclick="resetFormViewBook('cancel')"></button>
            </div>
            <div class="modal-body">
                <div class="html-content-view">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="resetFormViewBook('cancel')">CERRAR</button>                
            </div>
        </div>
    </div>
</div>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/dist/js-views/books/_view-book.js"></script>