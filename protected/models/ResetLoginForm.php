<?php

class ResetLoginForm extends CFormModel
{
	public $currentpassword;
	public $newpassword;
	public $confirmpassword;

	public function rules()
	{
		return array(
			array('currentpassword, newpassword, confirmpassword', 'required', 'message' => 'El campo {attribute} no puede estar vacío'),
			array('currentpassword', 'validateCurrentPassword'),
			array('newpassword', 'validateNewPassword'),
			array('confirmpassword', 'validateNewPassword'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'currentpassword' => 'Contraseña actual',
			'newpassword' => 'Nueva contraseña',
			'confirmpassword' => 'Confirmar contraseña'
		);
	}

	public function validateCurrentPassword()
	{
		$exists = Tbl_usuarios::model()->find('RowId=:id', [':id' => Yii::app()->user->rowId]);
		$verify = PasswordHash::verify($this->currentpassword, $exists->PasswordTemp);
		if ($verify['Status'] != 200) {
			$this->addError('currentpassword', 'Contraseña actual incorrecta');
			return false;
		}
		return true;
	}

	public function validateNewPassword()
	{
		if ($this->newpassword != $this->confirmpassword) {
			$this->addError('newpassword', 'Las contraseñas ingresadas no concuerdan');
			return false;
		}
		return true;
	}

	public function getUnifiedErrors()
	{
		$errors = $this->getErrors();
		$unifiedErrors = array();
		foreach ($errors as $attributeErrors) {
			foreach ($attributeErrors as $error) {
				$unifiedErrors[] = $error;
			}
		}
		return array_unique($unifiedErrors);
	}
}
