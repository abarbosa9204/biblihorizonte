<?php
class Tbl_libro_categoria extends CActiveRecord
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
        return 'TBL_LIBRO_CATEGORIA';
    }    
    public function createBookCategory($rowIdBook,$rowIdCategory)
    {

        $add                        =   new Tbl_libro_categoria();
        $add->RowIdLibro            =   $rowIdBook;
        $add->RowIdCategoria        =   $rowIdCategory;
        $add->Estado                =   1;
        $add->RowIdUsuarioCreador   =   Yii::app()->user->rowId;              
        $add->FechaCreacion         =   date('Y-m-d H:i:s');
        if (!$add->save()) {
            return Responses::getErrorValidation('Error asociando la categoria');
        }
        return Responses::getOk('Categoria asociada correctamente');
    }
    
    public function updateBookCategory($rowIdBook,$rowIdCategory)
    {
        $existe = Tbl_libro_categoria::model()->find(
            'RowIdLibro=:_RowIdLibro and RowIdCategoria=:_RowIdCategoria',
            [':_RowIdLibro' => $rowIdBook, ':_RowIdCategoria' => $rowIdCategory]
        );
        if($existe){
            $update = Tbl_libro_categoria::model()->updateAll(
                [
                    'Estado'                =>  1,
                    'RowIdUsuarioEditor'    =>  Yii::app()->user->rowId,
                    'FechaEdicion'          =>  date('Y-m-d H:i:s'),
                ],
                'RowIdLibro=:_RowIdLibro and RowIdCategoria=:_RowIdCategoria',
                [':_RowIdLibro' => $rowIdBook, ':_RowIdCategoria' => $rowIdCategory]
            );
            if (!$update) {
                return Responses::getErrorValidation('Error asociando la categoria');
            }
            return Responses::getOk('Categoria asociada correctamente');
        }else{
            return $this->createBookCategory($rowIdBook,$rowIdCategory);
        }
    }
}