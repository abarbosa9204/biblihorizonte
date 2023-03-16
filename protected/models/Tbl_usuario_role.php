<?php
class Tbl_usuario_role extends CActiveRecord
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
        return 'TBL_USUARIO_ROLE';
    }
    public function createUserRole($idUser,$idRole)
    {
        try {
            $add                           =    new Tbl_usuario_role();
            $add->RowIdUsuario             =    $idUser;
            $add->RowIdRole                =    $idRole;
            $add->Estado                   =    1;
            $add->RowIdUsuarioCreador      =    Yii::app()->user->rowId;
            $add->FechaCreacion            =    date('Y-m-d H:i:s');
            if ($add->save()) {
                return Responses::getCreated();
            } else {
                return Responses::getError();
            }
        } catch (\Throwable $th) {
            return Responses::getError();
        }
    }
    public function updateUserRole($idUser,$idNewRole)
    {        
        $existe = Tbl_usuario_role::model()->find(
            'RowIdUsuario=:_RowIdUsuario',
            [':_RowIdUsuario' => $idUser]
        );
        if($existe){
            $update = Tbl_usuario_role::model()->updateAll(
                [
                    'RowIdRole'             =>  $idNewRole,
                    'Estado'                =>  1,
                    'RowIdUsuarioEditor'    =>  Yii::app()->user->rowId,
                    'FechaEdicion'          =>  date('Y-m-d H:i:s'),
                ],
                'RowIdUsuario=:_RowIdUsuario',
                [':_RowIdUsuario' => $idUser]
            );
            if (!$update) {
                return Responses::getErrorValidation('Error actualizando el usuario');
            }
            return Responses::getOk('Usuario actualziado correctamente');        
        }else{
            return $this->createUserRole($idUser,$idNewRole);
        }
    }
}
