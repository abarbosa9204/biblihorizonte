<?php
class Tbl_libro_programa extends CActiveRecord
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
        return 'TBL_LIBRO_PROGRAMA';
    }

    public function createBookProgram($rowIdBook, $rowIdProgram)
    {
        $add                        =   new Tbl_libro_programa();
        $add->RowIdLibro            =   $rowIdBook;
        $add->RowIdPrograma         =   $rowIdProgram;
        $add->Estado                =   1;
        $add->RowIdUsuarioCreador   =   Yii::app()->user->rowId;
        $add->FechaCreacion         =   date('Y-m-d H:i:s');
        if (!$add->save()) {
            return Responses::getErrorValidation('Error asociando el programa');
        }
        return Responses::getOk('Programana asociado correctamente');
    }
    public function updateBookProgram($rowIdBook, $rowIdProgram)
    {
        $existe = Tbl_libro_programa::model()->find(
            'RowIdLibro=:_RowIdLibro and RowIdPrograma=:_RowIdPrograma',
            [':_RowIdLibro' => $rowIdBook, ':_RowIdPrograma' => $rowIdProgram]
        );
        if($existe){
            $update = Tbl_libro_programa::model()->updateAll(
                [
                    'Estado'                =>  1,
                    'RowIdUsuarioEditor'    =>  Yii::app()->user->rowId,
                    'FechaEdicion'          =>  date('Y-m-d H:i:s'),
                ],
                'RowIdLibro=:_RowIdLibro and RowIdPrograma=:_RowIdPrograma',
                [':_RowIdLibro' => $rowIdBook, ':_RowIdPrograma' => $rowIdProgram]
            );
            if (!$update) {
                return Responses::getErrorValidation('Error asociando el programa');
            }
            return Responses::getOk('Programana asociado correctamente');
        }else{
            return $this->createBookProgram($rowIdBook, $rowIdProgram);
        }
    }
}
