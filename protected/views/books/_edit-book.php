<!-- Modal -->
<div class="modal fade" id="edit-booksModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-booksModalLabel">EDITAR LIBRO</h5>
                <button type="button" class="btn-close" onclick="resetFormEditBook('cancel')"></button>
            </div>
            <div class="modal-body">
                <form action="#" method="POST" id="form-edit-book" name="form-edit-book" enctype="multipart/form-data">
                    <input type="hidden" value="" id="id-book-edit" name="id-book-edit" readonly>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control validate-text" id="edit-book-title" name="edit-book-title" placeholder="Titulo" maxlength="120">
                                <label for="edit-book-title">Titulo</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control validate-text" id="edit-book-author" name="edit-book-author" placeholder="Autor" maxlength="100" maxlength="120">
                                <label for="edit-book-author">Autor</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control validate-text" id="edit-book-publisher" name="edit-book-publisher" placeholder="Editorial" maxlength="100">
                                <label for="edit-book-publisher">Editorial</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating">
                                <textarea class="form-control validate-text" placeholder="Descripción" id="edit-book-description" name="edit-book-description" style="height: 108px" maxlength="400"></textarea>
                                <label for="edit-book-description">Descipción</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control validate-text" id="edit-book-content" name="edit-book-content" placeholder="Contenido" maxlength="300">
                                <label for="edit-book-content">Contenido</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control validate-text" id="edit-book-first-edition" name="edit-book-first-edition" placeholder="Primera edición" maxlength="100">
                                <label for="edit-book-first-edition">Primera edición</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control validate-text" id="edit-book-first-mention" name="edit-book-first-mention" placeholder="Mención primera edición" maxlength="50">
                                <label for="edit-book-first-mention">Mención primera edición</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control validate-text" id="edit-book-series" name="edit-book-series" placeholder="Serie" maxlength="10">
                                <label for="edit-book-series">Serie</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <?= CHtml::dropDownList(
                                    'edit-book-program[]',
                                    '',
                                    CHtml::listData(Tbl_programas::model()
                                        ->findAll(
                                            'Estado=:activate',
                                            array(':activate' => 1),
                                            array('order' => 'Programa')
                                        ), 'RowId', 'Programa'),
                                    array('class' => 'form-control', 'aria-describedby' => 'madeByHelp', 'data-live-search' => 'true', 'data-width' => '100%', 'multiple' => "multiple")
                                ); ?>
                                <label for="edit-book-program">Programa</label>
                                <span class="edit-number-items"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <?= CHtml::dropDownList(
                                    'edit-book-subject[]',
                                    '',
                                    CHtml::listData(Tbl_materia::model()
                                        ->findAll(
                                            'Estado=:activate',
                                            array(':activate' => 1),
                                            array('order' => 'Materia')
                                        ), 'RowId', 'Materia'),
                                    array('class' => 'form-control', 'aria-describedby' => 'madeByHelp', 'data-live-search' => 'true', 'data-width' => '100%', 'multiple' => "multiple")
                                ); ?>
                                <label for="edit-book-subject">Materia</label>
                                <span class="edit-number-items"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <?= CHtml::dropDownList(
                                    'edit-book-category[]',
                                    '',
                                    CHtml::listData(Tbl_categoria::model()
                                        ->findAll(
                                            'Estado=:activate',
                                            array(':activate' => 1),
                                            array('order' => 'Categoria')
                                        ), 'RowId', 'Categoria'),
                                    array('class' => 'form-control', 'aria-describedby' => 'madeByHelp', 'data-live-search' => 'true', 'data-width' => '100%', 'multiple' => "multiple")
                                ); ?>
                                <label for="edit-book-category">Categoria</label>
                                <span class="edit-number-items"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <?= CHtml::dropDownList('edit-book-language', '', CHtml::listData(Tbl_idioma::model()
                                    ->findAll(
                                        'Estado=:activate',
                                        array(':activate' => 1),
                                        array('order' => 'Idioma')
                                    ), 'RowId', 'Idioma'), array('size' => 1, 'class' => 'form-control', 'aria-describedby' => 'madeByHelp', 'multiple' => 'multiple', 'data-live-search' => 'true', 'data-width' => '100%', 'required' => true))
                                ?>
                                <label for="edit-book-language">Idioma</label>
                                <span class="edit-number-items"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <?= CHtml::dropDownList('edit-book-status', '', ['0' => 'Bloqueado', '1' => 'Activo'], array('size' => 1, 'class' => 'form-control bg-white', 'aria-describedby' => 'madeByHelp')); ?>
                                <label for="edit-book-status">Estado</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label for="edit-book-upload-file" style="cursor:pointer;">
                                                <i class="fas fa-cloud-upload-alt"></i> Subir archivo
                                            </label>
                                            <input id="edit-book-upload-file" name="edit-book-upload-file" type="file" style="display:none" accept="image/png, .jpeg, .jpg" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <div id="edit-book-upload-file-description" class="text-center text-success d-inline-block text-truncate" style="max-width: 100%;"></div>
                                            <small id="edit-book-upload-file-error" class="form-text text-danger"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="resetFormEditBook('cancel')">CANCELAR</button>
                <button type="button" class="btn btn-primary" onclick="submitFormEditBook()">EDITAR</button>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/dist/js-views/books/_edit-book.js"></script>