<?php
class Tbl_libro_materia extends CActiveRecord
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
        return 'TBL_LIBRO_MATERIA';
    }    
    public function createBookSubject($rowIdBook,$rowIdSubject)
    {
        $add                        =   new Tbl_libro_materia();
        $add->RowIdLibro            =   $rowIdBook;
        $add->RowIdMateria           =   $rowIdSubject;
        $add->Estado                =   1;
        $add->RowIdUsuarioCreador   =   Yii::app()->user->rowId;              
        $add->FechaCreacion         =   date('Y-m-d H:i:s');
        if (!$add->save()) {
            return Responses::getErrorValidation('Error asociando la materia');
        }
        return Responses::getOk('Materia asociada correctamente');
    }
    
    public function updateBookSubject($rowIdBook,$rowIdSubject)
    {
        $existe = Tbl_libro_materia::model()->find(
            'RowIdLibro=:_RowIdLibro and RowIdMateria=:_RowIdMateria',
            [':_RowIdLibro' => $rowIdBook, ':_RowIdMateria' => $rowIdSubject]
        );
        if($existe){
            $update = Tbl_libro_materia::model()->updateAll(
                [
                    'Estado'                =>  1,
                    'RowIdUsuarioEditor'    =>  Yii::app()->user->rowId,
                    'FechaEdicion'          =>  date('Y-m-d H:i:s'),
                ],
                'RowIdLibro=:_RowIdLibro and RowIdMateria=:_RowIdMateria',
                [':_RowIdLibro' => $rowIdBook, ':_RowIdMateria' => $rowIdSubject]
            );
            if (!$update) {
                return Responses::getErrorValidation('Error asociando la materia');
            }
            return Responses::getOk('Materia asociada correctamente');
        }else{
            return $this->createBookSubject($rowIdBook,$rowIdSubject);
        }
    }
}