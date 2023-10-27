<?php
class Vw_lista_libros extends CActiveRecord
{
    public function getDbConnection()
    {
        return Yii::app()->db;
    }
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
    public function tableName()
    {
        return 'VW_LISTA_LIBROS';
    }
    public function getListBooks($request_data)
    {
        $response = array();
        if (isset($request_data)) {
            $draw = $request_data['draw'];
            $rows = $request_data['start'];
            $rowperpage = $request_data['length'];
            $columnIndex = $request_data['order'][0]['column'];
            $columnName = $request_data['columns'][$columnIndex]['data'];
            $columnSortOrder = $request_data['order'][0]['dir'];
            $searchValue = $request_data['search']['value'];

            $resulset = Yii::app()->db->createCommand("select Distinct
                                                                        RowId
                                                                        ,Descripcion
                                                                        ,Contenido
                                                                        ,Titulo
                                                                        ,Autor
                                                                        ,PrimeraEdicion
                                                                        ,MencionPrimera
                                                                        ,Serie
                                                                        ,Editorial
                                                                        ,Url                                                                        
                                                                        ,FechaCreacion
                                                                        ,Creador
                                                                        ,RowIdEstado
                                                                        ,EstadoNombre
																	from
                                                                        VW_LISTA_LIBROS
																	where 
																		CONCAT(Titulo,Autor,Programa,Materia) LIKE '%" . $searchValue . "%'"
                . ($request_data['searchStatus'] != '' ? " and RowIdEstado='" . $request_data['searchStatus'] . "'" : "")
                . "order by " . ((($columnName == 'Libro') ? 'Titulo' : $columnName) . ' ' . $columnSortOrder) .
                ($rowperpage != -1 ? " OFFSET " . $rows . " ROWS FETCH NEXT " . $rowperpage . " ROWS ONLY;" : ""))->queryAll();

            $resulset2 = Yii::app()->db->createCommand("select Distinct
                                                                RowId
                                                                ,Descripcion
                                                                ,Contenido
                                                                ,Titulo
                                                                ,Autor
                                                                ,PrimeraEdicion
                                                                ,MencionPrimera
                                                                ,Serie
                                                                ,Editorial
                                                                ,Url                                                                
                                                                ,FechaCreacion
                                                                ,Creador
                                                                ,RowIdEstado
                                                                ,EstadoNombre
                                                             from
                                                                    VW_LISTA_LIBROS
                                                                where 
                                                                    CONCAT(Titulo,Autor,Programa,Materia) LIKE '%" . $searchValue . "%'"
                . ($request_data['searchStatus'] != '' ? " and RowIdEstado='" . $request_data['searchStatus'] . "'" : "")
                . "order by " . ((($columnName == 'Libro') ? 'Titulo' : $columnName) . ' ' . $columnSortOrder))->queryAll();

            $request_data = array();
            $img = $description = '';
            foreach ($resulset as $row) {
                $img = $description = $actions = '';
                $getData = Yii::app()->db->createCommand(
                    "DECLARE @rowIdOut nvarchar(250)
                    DECLARE @programaOut nvarchar(max)
                    DECLARE @materiaOut nvarchar(max)
                    DECLARE @categoriaOut nvarchar(max)
                    DECLARE @idiomasOut nvarchar(max)
                    DECLARE @result char(3)
                    EXECUTE SP_LIBRO_DEPENDENCIAS '" . $row['RowId'] . "', @rowIdOut OUTPUT,@programaOut OUTPUT,@materiaOut OUTPUT,@categoriaOut OUTPUT,@idiomasOut OUTPUT, @result OUTPUT
                    SELECT 
                        @rowIdOut as RowId
                        ,@programaOut as Programas
                        ,@materiaOut as Materias
                        ,@categoriaOut as Categorias
                        ,@idiomasOut as Idiomas
                        ,@result as Status"
                )->queryRow();


                if ($getData['Status'] == '200') {
                    $program    =   $getData['Programas'];
                    $category   =   $getData['Categorias'];
                    $subject    =   $getData['Materias'];
                    $languaje   =   $getData['Idiomas'];
                } else {
                    $program    =   '';
                    $category   =   '';
                    $subject    =   '';
                    $languaje   =   '';
                }
                
                if($row['Url']=='Url-no-exists'){
                    $img .= '<img class="card-img" src="' . Yii::app()->request->baseUrl . '/images/sin-imagen.png' . '" alt="' . $row['Titulo'] . '" style="width:42px;height:auto">';
                }else{
                    $img .= '<img class="card-img" src="' . Yii::app()->request->baseUrl . $row['Url'] . '" alt="' . $row['Titulo'] . '" style="width:42px;height:auto">';
                }

                if (in_array(Yii::app()->user->profile['Nombre'], ['Admin'])) {
                    $actions .= '<a href="javascript:void(0);" type="button" class="btn btn-xs waves-effect p-0" onclick="showFormEditBook(' . "'" . Encrypt::encryption($row['RowId']) . "'" . ')"><i class="bi bi-pencil-square" style="color:#B61020;font-size:20px;"></i> Editar</a>';
                }
                $actions .= '<a href="javascript:void(0);" type="button" class="btn btn-xs waves-effect p-0" onclick="showFormViewBook(' . "'" . Encrypt::encryption($row['RowId']) . "'" . ')"><i class="bi bi-binoculars-fill" style="color:#198754;font-size:20px;"></i> Detalle</a>';
                $estadoNombre = '';
                switch ($row['RowIdEstado']) {
                    case '8DE5DE44-8090-4936-81FE-3ABEA55046E0': //reservado
                        $estadoNombre = '<i class="bi bi-file-lock px-2" style="color:#00376C"></i><span class="badge bg-dark">' . $row['EstadoNombre'] . '</span>';
                        break;
                    case 'FF9EBAF1-A4CD-4143-8095-9CB96A4F2314': //disponible
                        $estadoNombre = '<i class="bi bi-check-circle-fill px-2 text-success"></i><span class="badge bg-success">' . $row['EstadoNombre'] . '</span>';
                        $actions .= '<a href="javascript:void(0);" type="button" class="btn btn-xs waves-effect p-0" onclick="showFormViewBook(' . "'" . Encrypt::encryption($row['RowId']) . "'" . ')"><i class="bi bi-journal-album" style="color:#198754;font-size:20px;"></i> Reservar</a>';
                        break;
                    case '6AD1D9A0-53DC-4709-9B54-F66BA6E433FE': //prestado
                        $estadoNombre = '<i class="bi bi-journal-x px-2" style="color:#712CF9"></i><span class="badge bg-dark" style="background:#712CF9 !important">' . $row['EstadoNombre'] . '</span>';
                        break;
                }
                
                $request_data[] = array(
                    'Libro'         =>  $img,
                    'Titulo'        =>  $row['Titulo'],
                    'Autor'         =>  $row['Autor'],
                    'Programa'      =>  $program,
                    'Materia'       =>  $subject,
                    'EstadoNombre'  =>  $estadoNombre,
                    'Acciones'      =>  $actions
                );
            }
            $response = array(
                'draw' => intval($draw),
                'recordsTotal' => count($resulset2),
                'recordsFiltered' => count($resulset2),
                'data' => $request_data
            );
        }
        return $response;
    }
}
