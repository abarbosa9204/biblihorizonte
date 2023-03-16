<section>
    <div class="row">
        <div class="col-xl-12 col-sm-12 col-md-12 shadow-lg p-1 mb-5">
            <div class="card-body">
                <h6 class="card-title mb-3 h6 m-0 font-weight-bold">LIBROS</h6>
                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered striped display compact table-hover table-sm" id="list-books" width="100%" cellspacing="0">
                                <thead class="align-middle text-center">
                                    <tr>
                                        <td class="text-center" style="width: 300px;max-width: 300px;min-width: 300px;">LIBRO</td>
                                        <td class="text-center" style="width:100%;max-width: 800px;min-width: 800px;">DESCRIPCIÓN</td>
                                        <!-- <td style="width: 10%;min-width:100px">ESTADO</td>
                                        <td style="width: 160px;min-width:160px">FECHA ACTUALIZACIÓN</td>
                                        <td style="width: 150px;min-width:150px"></td> -->
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