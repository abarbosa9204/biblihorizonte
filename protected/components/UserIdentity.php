<?php
/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		$user = new Tbl_usuarios();
		$data = $user->getByEmail($this->username);
		if($data['Status']=='200'){
			$verify=PasswordHash::verify($this->password,$data['data']['Password']);
			if($verify['Status']!=200){
				$this->errorCode = self::ERROR_PASSWORD_INVALID;
			}
		}		
		if($data['Status']!=200){
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		}else if ($data['data']['Correo'] != $this->username) {
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		} else if ($data['data']['Estado'] != "1") {
			$this->errorCode = self::ERROR_ACTIVE;
		} else {
			$role = new Vw_lista_usuario_role();			
			$roleAccess = $role->getById($data['data']['RowId']);			
			if($roleAccess['Status']=='200'){
				$this->setState('profile',$roleAccess['data']);
			}else{
				$this->setState('profile','');
			}
			yii::app()->user->setState('userSessionTimeout', time() + Yii::app()->params['sessionTimeoutSeconds']);			
			$this->setState('rowId',$data['data']['RowId']);
			$this->errorCode = self::ERROR_NONE;
			
		}
		return !$this->errorCode;
	}
}
