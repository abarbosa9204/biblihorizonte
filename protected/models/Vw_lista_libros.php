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
                                                                        ,Estado
                                                                        ,FechaCreacion
                                                                        ,Creador
																	from
                                                                        VW_LISTA_LIBROS
																	where 
																		CONCAT(Titulo,Autor) LIKE '%" . $searchValue . "%'"
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
                                                                ,Estado
                                                                ,FechaCreacion
                                                                ,Creador
                                                             from
                                                                    VW_LISTA_LIBROS
                                                                where 
                                                                    CONCAT(Titulo,Autor) LIKE '%" . $searchValue . "%'"
                . "order by " . ((($columnName == 'Libro') ? 'Titulo' : $columnName) . ' ' . $columnSortOrder))->queryAll();

            $request_data = array();
            $img = $description = '';
            foreach ($resulset as $row) {
                $img = $description = '';
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

                $img .= '<div class="d-flex justify-content-center"><div class="card" style="width: 300px;max-width: 300px;min-width: 300px;height: 500px;max-height: 500px;min-height: 500px">
                            <img class="card-img" src="' . Yii::app()->request->baseUrl . $row['Url'] . '" alt="' . $row['Titulo'] . '">
                            <div class="card-img-overlay d-flex align-items-end"><br>
                                <h6 class="card-title">Titulo: ' . $row['Titulo'] . '</h6>
                            </div>
                        </div></div>';

                $description .= '<div class="d-flex justify-content-center" style="height: 520px;">
                                    <div class="row" style="width: 100%;max-width: 900px;min-width: 900px;padding: 10px;">                                    
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title">Información básica ' . ($row['Estado'] == 1 ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-danger">Bloqueado</span>') . '</h5>
                                                <p class="card-text" style="max-height:74px"><strong>Descripción: </strong>' . (strlen($row['Descripcion']) > 200 ? substr($row['Descripcion'], 0, 200) . '...<a href="javascript:void(0);" onclick="showText(' . "'Descripcion','" . $row['RowId'] . "'" . ')" type="button" >[leer más]</a>' : $row['Descripcion']) . '</p>
                                                <p class="card-text" style="max-height:74px"><strong>Contenido: </strong>' . (strlen($row['Contenido']) > 200 ? substr($row['Contenido'], 0, 200) . '...<a href="javascript:void(0);" onclick="showText(' . "'Contenido','" . $row['RowId'] . "'" . ')" type="button" >[leer más]</a>' : $row['Contenido']) . '</p>
                                                <p class="card-text m-0"><strong>Primera edición: </strong>' . (strlen($row['PrimeraEdicion']) > 200 ? substr($row['PrimeraEdicion'], 0, 200) . '...<a href="javascript:void(0);" onclick="showText(' . "'PrimeraEdicion','" . $row['RowId'] . "'" . ')" type="button" >[leer más]</a>' : $row['PrimeraEdicion']) . '</p>
                                                <p class="card-text m-0"><strong>Mención primera: </strong>' . (strlen($row['MencionPrimera']) > 200 ? substr($row['MencionPrimera'], 0, 200) . '...<a href="javascript:void(0);" onclick="showText(' . "'MencionPrimera','" . $row['RowId'] . "'" . ')" type="button" >[leer más]</a>' : $row['MencionPrimera']) . '</p>
                                                <p class="card-text m-0"><strong>Serie: </strong>' . (strlen($row['Serie']) > 200 ? substr($row['Serie'], 0, 200) . '...<a href="javascript:void(0);" onclick="showText(' . "'Serie','" . $row['RowId'] . "'" . ')" type="button" >[leer más]</a>' : $row['Serie']) . '</p>
                                                <p class="card-text m-0"><strong>Editorial: </strong>' . (strlen($row['Editorial']) > 200 ? substr($row['Editorial'], 0, 200) . '...<a href="javascript:void(0);" onclick="showText(' . "'Editorial','" . $row['RowId'] . "'" . ')" type="button" >[leer más]</a>' : $row['Editorial']) . '</p>
                                                <p class="card-text m-0"><strong>Fecha de registro unihorizonte: </strong>' . (strlen($row['FechaCreacion']) > 200 ? substr($row['FechaCreacion'], 0, 200) . '...<a href="javascript:void(0);" onclick="showText(' . "'FechaCreacion','" . $row['RowId'] . "'" . ')" type="button" >[leer más]</a>' : $row['FechaCreacion']) . '</p>
                                                <p class="card-text m-0"><strong>Creador de registro: </strong>' . (strlen($row['Creador']) > 200 ? substr($row['Creador'], 0, 200) . '...<a href="javascript:void(0);" onclick="showText(' . "'Creador','" . $row['RowId'] . "'" . ')" type="button" >[leer más]</a>' : $row['Creador']) . '</p>
                                                <p class="card-text m-0"><strong>Programas: </strong>' . (strlen($program) > 80 ? substr($program, 0, 80) . '...<a href="javascript:void(0);" onclick="showText(' . "'Programas','" . $row['RowId'] . "'" . ')" type="button" >[leer más]</a>' : $program) . '</p>
                                                <p class="card-text m-0"><strong>Materias: </strong>' . (strlen($subject) > 80 ? substr($subject, 0, 80) . '...<a href="javascript:void(0);" onclick="showText(' . "'Materias','" . $row['RowId'] . "'" . ')" type="button" >[leer más]</a>' : $subject) . '</p>
                                                <p class="card-text m-0"><strong>Categoria: </strong>' . (strlen($category) > 80 ? substr($category, 0, 80) . '...<a href="javascript:void(0);" onclick="showText(' . "'Categoria','" . $row['RowId'] . "'" . ')" type="button" >[leer más]</a>' : $category) . '</p>
                                                <p class="card-text m-0"><strong>Idiomas: </strong>' . (strlen($languaje) > 80 ? substr($languaje, 0, 80) . '...<a href="javascript:void(0);" onclick="showText(' . "'Idiomas','" . $row['RowId'] . "'" . ')" type="button" >[leer más]</a>' : $languaje) . '</p>
                                            </div>';
                if (in_array(Yii::app()->user->profile['Nombre'], ['Admin'])) {
                    $description .= '<div style="position: absolute;right: 5px;top: 5px;">
                                        <a href="javascript:void(0);" type="button" class="btn btn-primary" onclick="showFormEditBook(' . "'" . Encrypt::encryption($row['RowId']) . "'" . ')"><i class="fa fa-edit"></i> Editar</a>
                                     </div>';
                }
                $description .= '</div>                                        
                                    </div>                                    
                                </div>';
                $request_data[] = array(
                    'Libro'             =>  $img,
                    'Descripcion'       =>  $description
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
