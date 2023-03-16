<?php
class Vw_lista_usuarios extends CActiveRecord
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
        return 'VW_LISTA_USUARIOS';
    }

    public function getListUsers($request_data)
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
															*
																	from
                                                                        VW_LISTA_USUARIOS
																	where 
																		CONCAT(NombreCompleto,Cedula) LIKE '%" . $searchValue . "%'"
                . "order by " . ((($columnName == 'Cedula') ? 'Nombre' : $columnName) . ' ' . $columnSortOrder) .
                ($rowperpage != -1 ? " OFFSET " . $rows . " ROWS FETCH NEXT " . $rowperpage . " ROWS ONLY;" : ""))->queryAll();

            $resulset2 = Yii::app()->db->createCommand("select Distinct
															*
                                                             from
                                                                    VW_LISTA_USUARIOS
                                                                where 
                                                                    CONCAT(NombreCompleto,Cedula) LIKE '%" . $searchValue . "%'"
                . "order by " . ((($columnName == 'Cedula') ? 'Nombre' : $columnName) . ' ' . $columnSortOrder))->queryAll();

            $request_data = array();

            foreach ($resulset as $row) {
                $request_data[] = array(
                    'Cedula'            =>  $row['Cedula'],
                    'NombreCompleto'    =>  $row['NombreCompleto'],
                    'Correo'            =>  $row['Correo'],
                    'Telefono'          =>  $row['Telefono'],
                    'Programa'          =>  $row['Programa'],
                    'Role'              =>  $row['Role'],
                    'Estado'            =>  ($row['Estado'] == 1) ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-danger">Bloqueado</span>',
                    'FechaCreacion'     =>  $row['FechaCreacion'],
                    'accion'            =>  '<a href="javascript:void(0);" onclick="showFormEditUser(' . "'" . Encrypt::encryption($row['RowId']) . "','edit'" . ')" type="button" title="Editar" data-html="true" data-content="Editar usuario" class=""><i class="bi bi-pencil-square" style="font-size:28px"></i></a>'
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
