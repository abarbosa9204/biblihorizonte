# biblihorizonte
Biblioteca

#Instalación de frontend
* Instalar la versión PHP 7.4 en el servidor
* Descargar Drivers para la conexión a sql server según el servidor
    php_pdo_sqlsrv_74_nts_x64.dll
    php_pdo_sqlsrv_74_ts_x64.dll
    php_pdo_sqlsrv_74_nts_x86.dll
    php_pdo_sqlsrv_74_ts_x86.dll
* Clonar repositorio en la ruta deseada (para descargar el repo es necesario instalar git). Url Repo: git clone https://github.com/abarbosa9204/biblihorizonte.git
* Dar permisos de escritura y lectura a la carpeta assets que se encuentra en la siguiente ruta dentro del proyecto: biblihorizonte/assets
* Mover carpetra "framework" que se encuentra en la ruta "bilibihorizonte/framework" al mismo nivel del proyecto para que pueda iniciar la aplicación
* Descargar backup de la base de datos y resturar en sql server. Ruta del backup: biblihorizonte/DbUnihorizonte.bak
* Configurar Cadena de conexión en la siguiente ruta dentro del proyecto: biblihorizonte/protected/config/main.php
    La cadena de conexión se encuentra en la línea 58 del archivo
    'db' => array(
			'class' => 'CDbConnection',
			'connectionString' => 'sqlsrv:server=servidor;Database=base de datos',
			'username' => 'usuario',
			'password' => 'password',
			'charset' => 'utf8',
		),

#Instalación de backend
* Clonar repo de APIRest realizada en .Net core 6 c#. Este backen permite realizar la comunicación concualquier aplicación, siempre y cuando se creen los servicios que se requieran
* Url Repo: https://github.com/abarbosa9204/UniHorizonteWebAPI.git
* En la siguiente URL encontrará un video con las indicaciones para realizar el deply de la apliación biblihorizonte/tutorial publicación backen c#.mp4