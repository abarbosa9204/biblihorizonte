<?php

use GuzzleHttp\Psr7\Response;

class Tbl_usuario_programa extends CActiveRecord
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
        return 'TBL_USUARIO_PROGRAMA';
    }
    public function createUserProgram($idUser, $idProgram)
    {
        try {
            $add                           =    new Tbl_usuario_programa();
            $add->RowIdUsuario             =    $idUser;
            $add->RowIdPrograma            =    $idProgram;
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
}
