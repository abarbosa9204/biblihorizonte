<?php
class Tbl_libro_idioma extends CActiveRecord
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
        return 'TBL_LIBRO_IDIOMA';
    }    
    public function createBookLanguage($rowIdBook,$rowIdLanguage)
    {
        $add                        =   new Tbl_libro_idioma();
        $add->RowIdLibro            =   $rowIdBook;
        $add->RowIdIdioma           =   $rowIdLanguage;
        $add->Estado                =   1;
        $add->RowIdUsuarioCreador   =   Yii::app()->user->rowId;              
        $add->FechaCreacion         =   date('Y-m-d H:i:s');
        if (!$add->save()) {
            return Responses::getErrorValidation('Error asociando el idioma');
        }
        return Responses::getOk('Idioma asociado correctamente');
    }
    
    public function updateBookLanguage($rowIdBook,$rowIdLanguage)
    {
        $existe = Tbl_libro_idioma::model()->find(
            'RowIdLibro=:_RowIdLibro and RowIdIdioma=:_RowIdIdioma',
            [':_RowIdLibro' => $rowIdBook, ':_RowIdIdioma' => $rowIdLanguage]
        );
        
        if($existe){            
            $update = Tbl_libro_idioma::model()->updateAll(
                [
                    'Estado'                =>  1,
                    'RowIdUsuarioEditor'    =>  Yii::app()->user->rowId,
                    'FechaEdicion'          =>  date('Y-m-d H:i:s'),
                ],
                'RowIdLibro=:_RowIdLibro and RowIdIdioma=:_RowIdIdioma',
                [':_RowIdLibro' => $rowIdBook, ':_RowIdIdioma' => $rowIdLanguage]
            );
            
            if (!$update) {
                return Responses::getErrorValidation('Error asociando el idioma');
            }
            return Responses::getOk('Idioma asociado correctamente');
        }else{
            return $this->createBookLanguage($rowIdBook,$rowIdLanguage);
        }
    }
}