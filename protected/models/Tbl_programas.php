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
}