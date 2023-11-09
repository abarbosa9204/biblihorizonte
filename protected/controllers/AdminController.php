<?php
date_default_timezone_set("America/Bogota");
class AdminController extends Controller
{
	public $layout = "//layouts/admin";
	/*public function filters()
	{
		return array(
			'accessControl',
			'postOnly + delete',
		);
	}

	public function accessRules()
	{
		return array(
			array(
				'allow',
				'actions' => array('logout', 'login'),
				'users' => array('*'),
				'message' => "No está autorizado, por favor comunicarse con el administrador para solicitar acceso",
			),
			array(
				'allow',
				'actions' => array(),
				'users' => array('?'),
				'message' => "No está autorizado, por favor comunicarse con el administrador para solicitar acceso",
			),
			// or
			/*array(
				'allow',
				'actions' => (isset(Yii::app()->user->array_aceesos) ? Yii::app()->user->array_aceesos : array('logout', 'login', 'index')),
				'users' => array('@'),
				//'expression' => 'Yii::app()->user->activatePostventa==1',
				'message' => "No está autorizado, por favor comunicarse con el administrador para solicitar acceso",
			),
			array(
				'deny', //SIN ACCESO GENERAL                                  
				'actions' => array(),
				'users' => array('*'),
				'message' => "",
				'deniedCallback' => array($this, 'accessError'),
			),
		);
	}*/
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha' => array(
				'class' => 'CCaptchaAction',
				'backColor' => 0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page' => array(
				'class' => 'CViewAction',
			),
		);
	}

	/**
	 * This is the action to handle external exceptions.
	 */

	public function accessError()
	{
		Yii::app()->runController('site/error');
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionUsers()
	{
		$this->layout = "//layouts/admin";
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('users');
	}

	/**
	 * Listar datos de usuarios
	 * @param string
	 * @return json
	 */
	public function actionGetListUsers()
	{
		$queryUsers = new Vw_lista_usuarios();
		$data = $queryUsers->getListUsers($_POST);
		echo CJSON::encode($data);
	}
	/**
	 * Crear usuario
	 * @param string
	 * @return json
	 */
	public function actionCreateUser()
	{
		$queryUsers = new Tbl_usuarios();
		$data = $queryUsers->createUser($_POST);
		echo CJSON::encode($data);
	}
	/**
	 * Editar Usuario
	 * @param string
	 * @return json
	 */
	public function actionEditUser()
	{
		$queryUsers = new Tbl_usuarios();
		$data = $queryUsers->editUser($_POST);
		echo CJSON::encode($data);
	}
	/**
	 * Consultar usuario por ID
	 * @param string
	 * @return json
	 */
	public function actionGetUserById()
	{
		$queryUsers = new Tbl_usuarios();
		$data = $queryUsers->getUserById(Encrypt::decryption($_POST['id']));
		echo CJSON::encode($data);
	}
	/**
	 * Consultar data de usuario autenticado
	 * @param string
	 * @return json
	 */
	public function actionGetUserAuth()
	{
		$queryUsers = new Tbl_usuarios();
		$data = $queryUsers->getUserAuth();
		echo CJSON::encode($data);
	}

	/**
	 * @arguments validr el estado de la sesión actual
	 * @param string action
	 * @return render, redirect
	 */
	public function beforeAction($action)
	{
		if (!Yii::app()->user->isGuest) { //logued
			if (yii::app()->user->getState('userSessionTimeout') < time()) {
				yii::app()->user->setState('userSessionTimeout', time() + Yii::app()->params['sessionTimeoutSeconds']);
			}
			return true;
		} else {
			Yii::app()->user->logout();
			$this->redirect('../site/login');
			return true;
		}
	}
}
