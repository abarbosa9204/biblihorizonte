<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Bibliohorizonte',
	'defaultController' => 'site/index',
	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		/*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'Enter Your Password Here',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		*/
	),

	// application components
	'components'=>array(

		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),

		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat' 		=> 'path',
			'showScriptName' 	=> false,
			'urlSuffix'			=> '.asp',
			'rules' 			=> array(
				//'<action:(index)>'=>'site/<action>',
				'<controller:\w+>/<id:\d+>' 				=> '<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>' 	=> '<controller>/<action>',
				'<controller:\w+>/<action:\w+>' 			=> '<controller>/<action>',
			),
		),
		
		'Smtpmail' => array(
			'class' => 'ext.smtpmail.PHPMailer',
			'Host' => 'smtp.office365.com', // Servidor SMTP de Outlook (Office 365)
			'Username' => 'angel_barbosa20212@unihorizonte.edu.co', // Tu dirección de correo institucional
			'Password' => 'Eliza/01177', // La contraseña de tu cuenta de correo institucional
			'Mailer' => 'smtp',
			'Port' => 587, // Puerto SMTP de Outlook (Office 365)
			'SMTPAuth' => true, // Habilitar autenticación SMTP
			'SMTPSecure' => 'tls', // Usar TLS como método de seguridad
		),
		

		// database settings are configured in database.php
		//'db'=>require(dirname(__FILE__).'/database.php'),
		'db' => array(
			'class' => 'CDbConnection',
			'connectionString' => 'sqlsrv:server=172.16.100.47;Database=DbUniHorizonte',
			'username' => 'Ultron', //'rcse2',
			'password' => 'Eq5istema5', //'Melissagnulinux0812***',
			//'nullConversion' => PDO::NULL_EMPTY_STRING
			'charset' => 'utf8',
			//'enableProfiling' => true, //este es el debug de la bd
			//'enableParamLogging'=>true,
			//'emulatePrepare'=>true, // con esto desactivado
		),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>YII_DEBUG ? null : 'site/error',
		),

		/*'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				
			),
		),*/

	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
		//'sessionTimeoutSeconds' => 60 * 20, //10 minutos
		//'sessionTimeoutSeconds' => 60 * 2000, //borrar
	),
);
