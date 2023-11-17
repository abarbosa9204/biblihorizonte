<?php

use GuzzleHttp\Psr7\Response;

class Tbl_libro_prestamo extends CActiveRecord
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
        return 'TBL_LIBRO_PRESTAMO';
    }

    public function generateReserve($dataReserve)
    {

        if (!isset($dataReserve)) {
            return Responses::getNoContent();
        }

        foreach ($dataReserve as $key => $value) {
            switch ($key) {
                case 'row-id-book-reserv':
                    $row_id_book_reserv = $value;
                    break;
                case 'reserv-name':
                    $reserv_name = $value;
                    break;
                case 'reserv-dni':
                    $reserv_dni = $value;
                    break;
                case 'reserv-email':
                    $reserv_email = $value;
                    break;
                case 'reserv-phone':
                    $reserv_phone = $value;
                    break;
                case 'reserv-date-init':
                    $reserv_date_init = $value;
                    break;
                case 'reserv-date-end':
                    $reserv_date_end = $value;
                    break;
            }
        }

        $exists = Tbl_libro::model()->find(
            'RowId=:_rowId and RowIdEstado=:_rowIdEstado',
            [':_rowId' => Encrypt::decryption($row_id_book_reserv), ':_rowIdEstado' => 'FF9EBAF1-A4CD-4143-8095-9CB96A4F2314']
        );

        if (!$exists) {
            return Responses::getNoContent('El libro no existe o no esta disponible.');
        }


        $criteria = new CDbCriteria;
        $criteria->addInCondition('RowIdEstadoPrestamo', array(
            '3BEB12E5-3632-4A11-8598-1C66F379AAA9',
            'FDE346B2-EECF-4901-A3E1-E7BD503FC571',
        ));
        $criteria->addCondition('RowIdLibro = :_RowIdLibro');
        $criteria->params['_RowIdLibro'] = Encrypt::decryption($row_id_book_reserv);
        $librosPrestamo = Tbl_libro_prestamo::model()->findAll($criteria);

        if ($librosPrestamo) {
            return Responses::getNoContent('El libro ya se encuentra reservado por otro usuario.');
        }

        //$dateEndNow =  date("d-m-Y",strtotime($reserv_date_end."+ 1 days")); 
        $dateInit = new DateTimeImmutable($reserv_date_init);
        $dateEnd = new DateTimeImmutable($reserv_date_end);

        $diff = $dateEnd->diff($dateInit);

        $currentDate = date('Y-m-d');

        if ($reserv_date_init < $currentDate) {
            return Responses::getErrorValidation('La fecha inicial no puede ser menor a la fecha actual.');
        }

        if ($diff->days > 8) {
            return Responses::getErrorValidation('La diferencia entre las fechas no puede ser mayor a 8 días.');
        } else if ($reserv_date_init == $reserv_date_end) {
            return Responses::getErrorValidation('Las fechas inicial y final no pueden ser iguales.');
        }

        $newid = '';
        $id = '';
        while ($newid == $id) :
            $id = Yii::app()->db->createCommand()
                ->select('newid() as id')
                ->queryRow();
            $result = Tbl_libro_prestamo::model()->find('RowIdPrestamo=:id', [':id' => $id['id']]);
            if (!$result) {
                break;
            }
        endwhile;

        $add = new Tbl_libro_prestamo();
        $add->RowIdPrestamo             = $id['id'];
        $add->RowIdLibro                = Encrypt::decryption($row_id_book_reserv);
        $add->RowIdUsuario              = Yii::app()->user->rowId;
        $add->RowIdEstadoPrestamo       = '3BEB12E5-3632-4A11-8598-1C66F379AAA9';
        $add->Telefono                  = $reserv_phone;
        $add->Fecha_reserva             = $reserv_date_init;
        //$add->Fecha_recogida            = null;
        $add->Fecha_debe_entregar       = $reserv_date_end;
        $add->Fecha_devuelve            = date("Y-m-d", strtotime($reserv_date_end . "+ 8 days"));
        $add->Estado                    = 1;
        $add->RowIdUsuarioCreador       = Yii::app()->user->rowId;
        $add->FechaCreacion             = date('Y-m-d H:i:s');
        // $add->RowIdUsuarioEditor        = '';
        // $add->FechaEdicion              = '';
        if (!$add->save()) {
            return Responses::getErrorValidation('Error en el proceso de creación');
        }

        $update = Tbl_libro::model()->updateAll(
            [

                'RowIdEstado'           =>  '8DE5DE44-8090-4936-81FE-3ABEA55046E0',
                'RowIdUsuarioEditor'    =>  Yii::app()->user->rowId,
                'FechaEdicion'          =>  date('Y-m-d H:i:s')
            ],
            'RowId=:id',
            [':id' => $add->RowIdLibro]
        );
        if ($update) {
            return Responses::getOk('Reserva realizada');
        }
        return Responses::getError();
    }

    public function cancelReservation($idBook)
    {
        $exists = Tbl_libro_prestamo::model()->find(
            'RowIdLibro=:_RowIdLibro and RowIdUsuario=:_RowIdUsuario',
            [
                ':_RowIdLibro' => $idBook,
                ':_RowIdUsuario' => Yii::app()->user->rowId
            ]
        );
        if (!$exists) {
            return Responses::getErrorValidation('La reserva no existe o ya fue actualizada y no se encuentra disponible.');
        }
        $updateCancel = Tbl_libro_prestamo::model()->updateAll(
            [
                'RowIdEstadoPrestamo'   =>  '1825B4F6-DBA0-4BCB-AEF2-100D2E193478',
                'RowIdUsuarioEditor'    =>  Yii::app()->user->rowId,
                'FechaEdicion'          =>  date('Y-m-d H:i:s')
            ],
            'RowIdLibro=:_RowIdLibro and RowIdUsuario=:_RowIdUsuario',
            [
                ':_RowIdLibro' => $idBook,
                ':_RowIdUsuario' => Yii::app()->user->rowId
            ]
        );
        if (!$updateCancel) {
            return Responses::getErrorValidation('Error durante el proceso de cancelación de la reserva.');
        }

        $update = Tbl_libro::model()->updateAll(
            [
                'RowIdEstado'           =>  'FF9EBAF1-A4CD-4143-8095-9CB96A4F2314',
                'RowIdUsuarioEditor'    =>  Yii::app()->user->rowId,
                'FechaEdicion'          =>  date('Y-m-d H:i:s')
            ],
            'RowId=:_RowIdLibro',
            [
                ':_RowIdLibro' => $idBook
            ]
        );
        if ($update) {
            return Responses::getOk('Reserva cancelada satisfactoriamente.');
        }
        return Responses::getErrorValidation('La reserva fue cancelada, pero no se actualizó en el inventario general.');
    }
}
