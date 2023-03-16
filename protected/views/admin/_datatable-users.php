<section>
    <div class="row">
        <div class="col-xl-12 col-sm-12 col-md-12 shadow-lg p-1 mb-5">
            <div class="card-body">
                <h6 class="card-title mb-3 h6 m-0 font-weight-bold">USUARIOS</h6>
                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered striped display compact table-hover table-sm" id="list-users" width="100%" cellspacing="0">
                                <thead class="bg-primary text-white align-middle text-center">
                                    <tr>
                                        <th style="min-width: 110px;max-width: 110px;">CÉDULA</th>
                                        <th style="width:5%;min-width:250px">NOMBRES Y APELLIDOS</th>
                                        <th style="min-width: 300px;width: 50% !important;">CORREO</th>
                                        <th style="width: 8%;min-width:150px">TELÉFONO</th>
                                        <th style="width: 5%;min-width:250px">PROGRAMA</th>
                                        <th style="width: 5%;min-width:120px">ROLE</th>
                                        <th style="width: 10%;min-width:100px">ESTADO</th>
                                        <th style="width: 160px;min-width:160px">FECHA CREACIÓN</th>
                                        <th style="width: 150px;min-width:150px">ACCIONES</th>
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
<script src="<?php echo Yii::app()->request->baseUrl; ?>/dist/js-views/admin/_datatable-users.js"></script>