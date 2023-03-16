<?php
class Tbl_programas extends CActiveRecord
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
        return 'TBL_PROGRAMAS';
    }
    public function getRandomRows($num)
    {
        $data = Yii::app()->db->createCommand(
            "SELECT TOP 6 *
            FROM TBL_PROGRAMAS
            WHERE Estado=1
            ORDER BY newid()"
        )->queryAll();
        if(!$data){
            return Responses::getNoContent();
        }
        return Responses::getOk($data);
    }
}
