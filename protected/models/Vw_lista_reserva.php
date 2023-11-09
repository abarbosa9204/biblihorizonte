<?php
class Vw_lista_reserva extends CActiveRecord
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
        return 'VW_LISTA_RESERVA';
    }
    public function getListBooksReservation($request_data)
    {
        if (isset($request_data['searchStatus'])) {
            $request_data['searchStatus'] == '';
        } else {
            $request_data['searchStatus'];
        }
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
                                                                    ,EstadoLibro
                                                                    ,RowIdEstadoPrestamo
                                                                    ,EstadoReserva
                                                                    ,RowIdUsuario
                                                                    ,Fecha_reserva
                                                                    ,Fecha_recogida
                                                                    ,Fecha_debe_entregar
                                                                    ,Fecha_devuelve
                                                                    ,Fecha_devolvio
                                                                    ,DiasVencido
                                                                    ,Estado
                                                                from
                                                                    VW_LISTA_RESERVA
                                                                where 
                                                                    RowIdUsuario ='" . Yii::app()->user->rowId . "'
                                                                     AND CONCAT(Titulo,Autor,Programa,Materia) LIKE '%" . $searchValue . "%'"
                . ($request_data['searchStatus'] != '' ? " and RowIdEstadoPrestamo='" . $request_data['searchStatus'] . "'" : "")
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
                                                                    ,EstadoLibro
                                                                    ,RowIdEstadoPrestamo
                                                                    ,EstadoReserva
                                                                    ,RowIdUsuario
                                                                    ,Fecha_reserva
                                                                    ,Fecha_recogida
                                                                    ,Fecha_debe_entregar
                                                                    ,Fecha_devuelve
                                                                    ,Fecha_devolvio
                                                                    ,DiasVencido
                                                                    ,Estado
                                                                from
                                                                    VW_LISTA_RESERVA
                                                                where 
                                                                    RowIdUsuario ='" . Yii::app()->user->rowId . "'
                                                                     AND CONCAT(Titulo,Autor,Programa,Materia) LIKE '%" . $searchValue . "%'"
                . ($request_data['searchStatus'] != '' ? " and RowIdEstadoPrestamo='" . $request_data['searchStatus'] . "'" : "")
                . "order by " . ((($columnName == 'Libro') ? 'Titulo' : $columnName) . ' ' . $columnSortOrder))->queryAll();

            $request_data = array();
            $img = $description = $manage = $color = '';
            foreach ($resulset as $row) {
                $img = $description = $actions = $manage = $color = '';
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

                if ($row['Url'] == 'Url-no-exists') {
                    $img .= '<img class="card-img" src="' . Yii::app()->request->baseUrl . '/images/sin-imagen.png' . '" alt="' . $row['Titulo'] . '" style="width:42px;height:auto">';
                } else {
                    $img .= '<img class="card-img" src="' . Yii::app()->request->baseUrl . $row['Url'] . '" alt="' . $row['Titulo'] . '" style="width:42px;height:auto">';
                }

                $actions .= '<a href="javascript:void(0);" type="button" class="btn btn-xs waves-effect p-0" onclick="showFormViewBook(' . "'" . Encrypt::encryption($row['RowId']) . "','detail'" . ')"><i class="bi bi-binoculars-fill" style="color:#198754;font-size:20px;"></i> Detalle</a>';
                $estadoNombre = $observations = '';
                switch ($row['RowIdEstadoPrestamo']) {
                    case '1825B4F6-DBA0-4BCB-AEF2-100D2E193478': //RESERVA CANCELADA
                        $estadoNombre = '<i class="bi bi-x-octagon-fill px-2 text-danger"></i><span class="badge bg-danger">' . $row['EstadoReserva'] . '</span>';
                        $observations = 'La reserva fue cancelada.';
                        break;
                    case '3BEB12E5-3632-4A11-8598-1C66F379AAA9': //RESERVADO SIN RECOGER
                        $estadoNombre = '<i class="bi bi-file-lock px-2" style="color:#00376C"></i><span class="badge bg-dark">' . $row['EstadoReserva'] . '</span>';
                        $observations = 'La reserva fue realizada el día <b>' . $row['Fecha_reserva'] . '</b> y no has recogido el libro.';
                        $actions .= '<a href="javascript:void(0);" type="button" class="btn btn-xs waves-effect p-0" onclick="cancelReservation(' . "'" . Encrypt::encryption($row['RowId']) . "','cancel'" . ')"><i class="bi bi-x-octagon-fill text-danger" style="font-size:20px;"></i> Cancelar</a>';
                        $color = '#fff3cd';
                        break;
                    case '186654F1-CD2D-4A57-9EFB-B791C7D8D7BA': //RESERVA VENCIDA
                        $estadoNombre = '<i class="bi bi-calendar-x-fill px-2" style="color:#712CF9"></i><span class="badge bg-dark" style="background:#712CF9 !important">' . $row['EstadoReserva'] . '</span>';
                        $observations = 'Tu reserva venció el día <b>' . $row['Fecha_debe_entregar'] . '</b>. La reserva se efectuó el día <b>' . $row['Fecha_reserva'] . '</b> y ahora presenta un retraso de <b>' . $row['DiasVencido'] . ' día(s)</b>.';
                        $color = '#f8d7da';
                        break;
                    case '98FBFEF8-0BC0-4D3F-82C8-D2B8EAA50029': //ENTREGADO
                        $estadoNombre = '<i class="bi bi-check-circle-fill px-2 text-success"></i><span class="badge bg-success">' . $row['EstadoReserva'] . '</span>';
                        $observations = 'Entregaste el libro a la biblioteca a tiempo.';
                        break;
                    case 'FDE346B2-EECF-4901-A3E1-E7BD503FC571': //RESERVA EN CURSO
                        $estadoNombre = '<i class="bi bi-alarm px-2 text-info"></i><span class="badge bg-info">' . $row['EstadoReserva'] . '</span>';
                        $observations = 'Tu reserva comenzó el día <b>' . $row['Fecha_recogida'] . '</b>. Por favor, asegúrate de devolver el libro antes del <b>' . $row['Fecha_debe_entregar'] . '</b>, sin falta';
                        break;
                    default:
                        break;
                }

                $request_data[] = array(
                    'Libro'         =>  $img,
                    'Titulo'        =>  $row['Titulo'],
                    'Autor'         =>  $row['Autor'],
                    'Programa'      =>  $program,
                    'Materia'       =>  $subject,
                    'EstadoReserva' =>  $estadoNombre,
                    'Observaciones' =>  $observations,
                    'Acciones'      =>  $actions,
                    'Color'         =>  $color,
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
