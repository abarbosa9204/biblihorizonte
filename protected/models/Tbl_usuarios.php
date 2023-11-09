<?php

use GuzzleHttp\Psr7\Response;

class Tbl_usuarios extends CActiveRecord
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
        return 'TBL_USUARIO';
    }
    public function getByEmail($email)
    {
        $exists = Tbl_usuarios::model()->find('Correo=:email', [':email' => $email]);
        if ($exists) {
            return Responses::getOk($exists);
        }
        return Responses::getNoContent();
    }

    public function createUser($request_data)
    {
        if (!isset($request_data['dataForm'])) {
            return Responses::getNoContent();
        }

        foreach ($request_data['dataForm'] as $key => $value) {
            switch ($value['name']) {
                case 'create-user-person-id':
                    $create_user_person_id      = $value['value'];
                    break;
                case 'create-user-name':
                    $create_user_name           = $value['value'];
                    break;
                case 'create-user-surname':
                    $create_user_surname        = $value['value'];
                    break;
                case 'create-user-email':
                    $create_user_email          = strtolower($value['value']);
                    break;
                case 'create-user-phone':
                    $create_user_phone          = $value['value'];
                    break;
                case 'create-user-program':
                    $create_user_program        = $value['value'];
                    break;
                case 'create-user-role':
                    $create_user_role           = $value['value'];
                    break;
                case 'create-user-password':
                    $create_user_password       = $value['value'];
                    break;
            }
        }

        $cc = Tbl_usuarios::model()->find('Cedula=:cc', [':cc' => $create_user_person_id]);
        if ($cc) {
            return Responses::getErrorValidation('Ya existe un usuario el documento ' . $create_user_person_id);
        }
        $email = Tbl_usuarios::model()->find('Correo=:email', [':email' => $create_user_email]);
        if ($email) {
            return Responses::getErrorValidation('Ya existe un usuario el email ' . $create_user_email);
        }

        //Crear nuevo usuario        
        $add = new Tbl_usuarios();
        $add->Cedula                =   $create_user_person_id;
        $add->Nombre                =   $create_user_name;
        $add->Apellido              =   $create_user_surname;
        $add->Correo                =   $create_user_email;
        $add->Telefono              =   $create_user_phone;
        $add->Password              =   PasswordHash::generate($create_user_password)['data']['password'];
        $add->Estado                =   0;
        $add->RowIdUsuarioCreador   =   Yii::app()->user->rowId;
        $add->FechaCreacion         =   date('Y-m-d H:i:s');
        if (!$add->save()) {
            return Responses::getErrorValidation('Error en el proceso de creación');
        }
        $exists = Tbl_usuarios::model()->find('Correo=:email', [':email' => $add->Correo]);
        if ($exists) {
            $newRole = new Tbl_usuario_role();
            $newProgram = new Tbl_usuario_programa();
            $program = $newProgram->createUserProgram($exists->RowId, $create_user_program);
            if ($program['Status'] != 201) {
                return Responses::getErrorValidation('Error asociando el programa al usuario');
            }
            $role = $newRole->createUserRole($exists->RowId, $create_user_role);
            if ($role['Status'] != 201) {
                return Responses::getErrorValidation('Error asociando el role al usuario');
            }
            return Responses::getOk('Usuario creado correctamente');
        } else {
            return Responses::getErrorValidation('El usuario no exites');
        }
    }

    public function getUserById($id)
    {
        $exists = Vw_lista_usuarios::model()->find('RowId=:id', [':id' => $id]);
        if (!$exists) {
            return Responses::getNoContent();
        }
        return Responses::getOk([
            'RowIdHash' => Encrypt::encryption($exists->RowId),
            'RowId' => $exists->RowId,
            'Cedula' => $exists->Cedula,
            'Nombre' => $exists->Nombre,
            'Apellido' => $exists->Apellido,
            'NombreCompleto' => $exists->NombreCompleto,
            'Correo' => $exists->Correo,
            'Telefono' => $exists->Telefono,
            'Estado' => $exists->Estado,
            'RowIdPrograma' => $exists->RowIdPrograma,
            'RowIdRole' => $exists->RowIdRole,
            'FechaCreacion' => $exists->FechaCreacion
        ]);
    }

    public function editUser($request_data)
    {
        if (!isset($request_data['dataForm'])) {
            return Responses::getNoContent();
        }

        foreach ($request_data['dataForm'] as $key => $value) {
            switch ($value['name']) {
                case 'id-user-edit':
                    $id_user_edit             = Encrypt::decryption($value['value']);
                    break;
                case 'edit-user-person-id':
                    $edit_user_person_id      = $value['value'];
                    break;
                case 'edit-user-name':
                    $edit_user_name           = $value['value'];
                    break;
                case 'edit-user-surname':
                    $edit_user_surname        = $value['value'];
                    break;
                case 'edit-user-email':
                    $edit_user_email          = strtolower($value['value']);
                    break;
                case 'edit-user-phone':
                    $edit_user_phone          = $value['value'];
                    break;
                case 'edit-user-program':
                    $edit_user_program        = $value['value'];
                    break;
                case 'edit-user-role':
                    $edit_user_role           = $value['value'];
                    break;
                case 'edit-user-password':
                    $edit_user_password       = $value['value'];
                    break;
                case 'edit-user-status':
                    $edit_user_status         = $value['value'];
                    break;
            }
        }

        $exists = Tbl_usuarios::model()->find('RowId=:id', [':id' => $id_user_edit]);
        if (!$exists) {
            return Responses::getErrorValidation('El usuario no existe');
        }
        /*if ($exists->Correo == $edit_user_email) {
            return Responses::getErrorValidation('Ya existe un usuario el email ' . $edit_user_email);
        }*/

        if ($edit_user_password != '') {
            $rsPassHash = PasswordHash::generate($edit_user_password);
            if ($rsPassHash['Status'] == '200') {
                $passHash = $rsPassHash['data']['password'];
            } else {
                return Responses::getErrorValidation('Error generando el nuevo password.');
            }
        } else {
            $passHash = $exists->Password;
        }

        $update = Tbl_usuarios::model()->updateAll(
            [
                'Nombre'                =>  $edit_user_name,
                'Apellido'              =>  $edit_user_surname,
                'Correo'                =>  $edit_user_email,
                'Telefono'              =>  $edit_user_phone,
                'Password'              =>  $passHash,
                'Estado'                =>  $edit_user_status,
                'RowIdUsuarioEditor'    =>  $id_user_edit,
                'FechaEdicion'          =>  date('Y-m-d H:i:s')
            ],
            'RowId=:id',
            [':id' => $id_user_edit]
        );
        if ($update) {
            $editUser = new Tbl_usuario_role();
            $edit = $editUser->updateUserRole($id_user_edit, $edit_user_role);
            if ($edit['Status'] != '200') {
                return $edit;
            }
            $update = Tbl_usuario_programa::model()->updateAll(
                [
                    'Estado'                =>  0,
                    'RowIdUsuarioEditor'    =>  Yii::app()->user->rowId,
                    'FechaEdicion'          =>  date('Y-m-d H:i:s'),
                ],
                'RowIdUsuario=:_RowIdUsuario',
                [':_RowIdUsuario' => $id_user_edit]
            );
            if (!$update) {
                return Responses::getErrorValidation('Error realizando la actualización del programa.');
            }
            $editProgram = new Tbl_usuario_programa();
            $edit = $editProgram->updateUserProgram($id_user_edit, $edit_user_program);
            if ($edit['Status'] != '200') {
                return $edit;
            }
            return Responses::getOk('Usuario actualizado correctamente');
        } else {
            return Responses::getErrorValidation('Error realizando la actualización del usuario.');
        }
    }
    public function passwordRecovery($email)
    {
        $rs = $this->getByEmail($email['email']);
        if ($rs['Status'] == 200) {
            $rsPassHash = PasswordHash::generate('UH' . $rs['data']['Cedula']);
            if ($rsPassHash['Status'] == '200') {
                $passHash = $rsPassHash['data']['password'];
            } else {
                return Responses::getErrorValidation('Error generando el nuevo password.');
            }
            $update = Tbl_usuarios::model()->updateAll(
                [
                    'PasswordTemp'            =>  $passHash,
                    'UpdatePassword'        =>  1,
                    'RowIdUsuarioEditor'    =>  $rs['data']['RowId'],
                    'FechaEdicion'          =>  date('Y-m-d H:i:s')
                ],
                'RowId=:id',
                [':id' => $rs['data']['RowId']]
            );

            if ($update) {
                $from = 'tu_correo@outlook.com'; // La dirección de correo del remitente
                $subject = 'Recupeación de contrseña'; // El asunto del correo
                $body = 'Su nueva contraseña temporal es: '; // El contenido del correo
                $emails = [$email['email']]; // Un arreglo de destinatarios            
                //$addCC = ['copia1@example.com', 'copia2@example.com']; // Un arreglo de destinatarios en copia (CC)    
                $rsEmail = SendMailBH::notification($from, $subject, $body, $emails);
                if ($rsEmail['Status'] != 200) {
                    return Responses::getErrorCustom('Se realizo la actualización de la contraseña, pero el email no fue generado. <b>Contraseña: ' . 'UH' . $rs['data']['Cedula'] . '</b>');
                }
                return Responses::getOk('Solicitud procesada correctamente, su nueva contraseña temporal es <b>' . 'UH' . $rs['data']['Cedula'] . '</b>');
            }
        }
        return Responses::getErrorCustom('El email ingresado no existe');
    }

    public function resetPassword($pass)
    {
        $rsPassHash = PasswordHash::generate($pass);
        if ($rsPassHash['Status'] == '200') {
            $passHash = $rsPassHash['data']['password'];
        } else {
            return Responses::getErrorValidation('Error generando el nuevo password.');
        }
        $update = Tbl_usuarios::model()->updateAll(
            [
                'Password'              =>  $passHash,
                'UpdatePassword'        =>  0,
                'RowIdUsuarioEditor'    =>  Yii::app()->user->rowId,
                'FechaEdicion'          =>  date('Y-m-d H:i:s')
            ],
            'RowId=:id',
            [':id' => Yii::app()->user->rowId]
        );

        if ($update) {
            return Responses::getOk();
        }
        return Responses::getErrorCustom('Error realizando la asignación de la nueva contraseña');
    }

    public function getUserAuth()
    {
        $user = Tbl_usuarios::model()->find('RowId=:rowId', [':rowId' => Yii::app()->user->rowId]);
        if ($user) {
            return Responses::getOk($user, 'Procesado correctamente');
        }
        return Responses::getNoContent();
    }
}
