<?php
class Vw_lista_usuario_role extends CActiveRecord
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
        return 'VW_LISTA_USUARIO_ROLE';
    }
    public function getById($idUser)
    {
        $exists = Vw_lista_usuario_role::model()->find('RowIdUsuario=:id', [':id' => $idUser]);
        if ($exists) {
            return Responses::getOk($exists);
        }
        return Responses::getNoContent();
    }
}
