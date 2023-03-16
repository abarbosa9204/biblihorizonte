<!-- Modal -->
<div class="modal fade" id="booksModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="booksModalLabel">REGISTRAR LIBRO</h5>
                <button type="button" class="btn-close" onclick="resetFormCreateBook('cancel')"></button>
            </div>
            <div class="modal-body">
                <form action="#" method="POST" id="form-create-book" name="form-create-book" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control validate-text" id="create-book-title" name="create-book-title" placeholder="Titulo" maxlength="120">
                                <label for="create-book-title">Titulo</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control validate-text" id="create-book-author" name="create-book-author" placeholder="Autor" maxlength="100" maxlength="120">
                                <label for="create-book-author">Autor</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control validate-text" id="create-book-publisher" name="create-book-publisher" placeholder="Editorial" maxlength="100">
                                <label for="create-book-publisher">Editorial</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating">
                                <textarea class="form-control validate-text" placeholder="Descripción" id="create-book-description" name="create-book-description" style="height: 108px" maxlength="400"></textarea>
                                <label for="create-book-description">Descipción</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control validate-text" id="create-book-content" name="create-book-content" placeholder="Contenido" maxlength="300">
                                <label for="create-book-content">Contenido</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control validate-text" id="create-book-first-edition" name="create-book-first-edition" placeholder="Primera edición" maxlength="100">
                                <label for="create-book-first-edition">Primera edición</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control validate-text" id="create-book-first-mention" name="create-book-first-mention" placeholder="Mención primera edición" maxlength="50">
                                <label for="create-book-first-mention">Mención primera edición</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control validate-text" id="create-book-series" name="create-book-series" placeholder="Serie" maxlength="10">
                                <label for="create-book-series">Serie</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <?= CHtml::dropDownList(
                                    'create-book-program[]',
                                    '',
                                    CHtml::listData(Tbl_programas::model()
                                        ->findAll(
                                            'Estado=:activate',
                                            array(':activate' => 1),
                                            array('order' => 'Programa')
                                        ), 'RowId', 'Programa'),
                                    array('class' => 'form-control', 'aria-describedby' => 'madeByHelp', 'data-live-search' => 'true', 'data-width' => '100%', 'multiple' => "multiple")
                                ); ?>
                                <label for="create-book-program">Programa</label>
                                <span class="edit-number-items"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <?= CHtml::dropDownList(
                                    'create-book-subject[]',
                                    '',
                                    CHtml::listData(Tbl_materia::model()
                                        ->findAll(
                                            'Estado=:activate',
                                            array(':activate' => 1),
                                            array('order' => 'Materia')
                                        ), 'RowId', 'Materia'),
                                    array('class' => 'form-control', 'aria-describedby' => 'madeByHelp', 'data-live-search' => 'true', 'data-width' => '100%', 'multiple' => "multiple")
                                ); ?>
                                <label for="create-book-subject">Materia</label>
                                <span class="edit-number-items"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <?= CHtml::dropDownList(
                                    'create-book-category[]',
                                    '',
                                    CHtml::listData(Tbl_categoria::model()
                                        ->findAll(
                                            'Estado=:activate',
                                            array(':activate' => 1),
                                            array('order' => 'Categoria')
                                        ), 'RowId', 'Categoria'),
                                    array('class' => 'form-control', 'aria-describedby' => 'madeByHelp', 'data-live-search' => 'true', 'data-width' => '100%', 'multiple' => "multiple")
                                ); ?>
                                <label for="create-book-category">Categoria</label>
                                <span class="edit-number-items"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <?= CHtml::dropDownList('create-book-language', '', CHtml::listData(Tbl_idioma::model()
                                    ->findAll(
                                        'Estado=:activate',
                                        array(':activate' => 1),
                                        array('order' => 'Idioma')
                                    ), 'RowId', 'Idioma'), array('size' => 1, 'class' => 'form-control', 'aria-describedby' => 'madeByHelp', 'multiple' => 'multiple', 'data-live-search' => 'true', 'data-width' => '100%', 'required' => true))
                                ?>
                                <label for="create-book-language">Idioma</label>
                                <span class="edit-number-items"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label for="create-book-upload-file" style="cursor:pointer;">
                                                <i class="fas fa-cloud-upload-alt"></i> Subir archivo
                                            </label>
                                            <input id="create-book-upload-file" name="create-book-upload-file" type="file" style="display:none" accept="image/png, .jpeg, .jpg" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <div id="create-book-upload-file-description" class="text-center text-success d-inline-block text-truncate" style="max-width: 100%;"></div>
                                            <small id="create-book-upload-file-error" class="form-text text-danger"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="resetFormCreateBook('cancel')">CANCELAR</button>
                <button type="button" class="btn btn-primary" onclick="submitFormCreateBook()">CREAR</button>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/dist/js-views/books/_register-book.js"></script>