<style>
    #list-books tbody td {
        text-align: center;
        white-space: break-spaces;
    }

    .select2-container .select2-selection {
        overflow: hidden;
    }
</style>
<section>
    <div class="row">
        <div class="col-xl-12 col-sm-12 col-md-12 shadow-lg p-1 mb-5">
            <div class="card-body">
                <h6 class="card-title mb-3 h6 m-0 font-weight-bold">LIBROS</h6>
                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <?= CHtml::dropDownList('search-book-status', '', CHtml::listData(Tbl_libro_estado::model()->findAll('Estado=:activate', [':activate' => 1], array('order' => 'Id')), 'RowId', 'EstadoNombre'), array('prompt' => '-Seleccione-', 'size' => 1, 'class' => 'form-control bg-white', 'aria-describedby' => 'madeByHelp')); ?>
                                    <label for="search-book-status">Filtrar por estado</label>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="table-responsive">
                            <table class="table table-bordered striped display compact table-hover table-sm" id="list-books" width="100%" cellspacing="0">
                                <thead class="align-middle text-center">
                                    <tr>
                                        <td class="text-center">LIBRO</td>
                                        <td class="text-center" style="min-width: 160px">TÍTULO</td>
                                        <td class="text-center" style="min-width: 160px">AUTOR</td>
                                        <td class="text-center">PROGRAMA(S)</td>
                                        <td class="text-center">MATERIA(S)</td>
                                        <td class="text-center" style="min-width: 160px">ESTADO</td>
                                        <td class="text-center">ACCIONES</td>
                                    </tr>
                                </thead>
                                <tbody class="align-middle"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Init datatable -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/dist/js-views/books/_datatable-books.js"></script>