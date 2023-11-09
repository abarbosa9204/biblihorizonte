<?php

use GuzzleHttp\Psr7\Response;

class Tbl_libro extends CActiveRecord
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
        return 'TBL_LIBRO';
    }
    public function createBook($request_data, $request_dataFile)
    {
        if (!isset($request_data)) {
            return Responses::getNoContent();
        }

        foreach ($request_data as $key => $value) {
            switch ($key) {
                case 'create-book-title':
                    $create_book_title              =   $value;
                    break;
                case 'create-book-author':
                    $create_book_author             =   $value;
                    break;
                case 'create-book-publisher':
                    $create_book_publisher          =   $value;
                    break;
                case 'create-book-description':
                    $create_book_description        =   $value;
                    break;
                case 'create-book-content':
                    $create_book_content            =   $value;
                    break;
                case 'create-book-first-edition':
                    $create_book_first_edition      =   $value;
                    break;
                case 'create-book-first-mention':
                    $create_book_first_mention      =   $value;
                    break;
                case 'create-book-series':
                    $create_book_series             =   $value;
                    break;
                case 'create-book-program':
                    $create_book_program[]          =   $value;
                    break;
                case 'create-book-subject':
                    $create_book_subject[]          =   $value;
                    break;
                case 'create-book-category':
                    $create_book_category[]         =   $value;
                    break;
                case 'create-book-language':
                    $create_book_language[]         =   $value;
                    break;
            }
        }

        $newid = '';
        $id = '';
        while ($newid == $id) :
            $id = Yii::app()->db->createCommand()
                ->select('newid() as id')
                ->queryRow();
            $result = Tbl_libro::model()->find('RowId=:id', [':id' => $id['id']]);
            if (!$result) {
                break;
            }
        endwhile;

        try {
            $add                            =   new Tbl_libro();
            $add->RowId                     =   $id['id'];
            $add->Descripcion               =   $create_book_description;
            $add->Contenido                 =   $create_book_content;
            $add->Titulo                    =   $create_book_title;
            $add->Autor                     =   $create_book_author;
            $add->PrimeraEdicion            =   $create_book_first_edition;
            $add->MencionPrimera            =   $create_book_first_mention;
            $add->Serie                     =   $create_book_series;
            $add->Editorial                 =   $create_book_publisher;
            $add->Url                       =   'Url-no-exists';
            $add->RowIdEstado               =   'FF9EBAF1-A4CD-4143-8095-9CB96A4F2314'; //disponible
            $add->RowIdUsuarioCreador       =   Yii::app()->user->rowId;
            $add->FechaCreacion             =   date('Y-m-d H:i:s');
            if (!$add->save()) {
                return Responses::getErrorValidation('Error en el proceso de creación');
            }
            try {
                if (isset($request_dataFile['create-book-upload-file']) && $request_dataFile['create-book-upload-file']['name']) {
                    $fichero    =   $request_dataFile['create-book-upload-file'];
                    $tipo       =   $request_dataFile['create-book-upload-file']['type'];
                    $tamano     =   $request_dataFile['create-book-upload-file']['size'];
                    $temp       =   $request_dataFile['create-book-upload-file']['tmp_name'];
                    $ext        =   pathinfo($fichero['name'], PATHINFO_EXTENSION);
                    $porcentaje = 0.5;
                    list($ancho, $alto) = getimagesize($temp);
                    $nuevo_ancho = 250; //$ancho * $porcentaje;
                    $nuevo_alto = 350; //$alto * $porcentaje;
                    //Redimensionar
                    $imagen_p = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);

                    if ($ext == 'png') {
                        $imagen = imagecreatefrompng($temp);
                    } else if ($ext == 'jpeg') {
                        $imagen = imagecreatefromjpeg($temp);
                    } else if ($ext == 'jpg') {
                        $imagen = imagecreatefromjpeg($temp);
                    }
                    imagecopyresampled($imagen_p, $imagen, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho, $alto);
                    /* Sobreescribimos la imagen original con la reescalada */
                    imagejpeg($imagen_p, $temp);
                    /* Actualizo el tamaño al que tiene la imagen reescalada */
                    $request_dataFile['create-book-upload-file']['size'] = filesize($temp);

                    $fileName = preg_replace('/\s+/', '-', $fichero['name']);
                    $fileName = preg_replace('/[^A-Za-z0-9.\-]/', '', $fileName);
                    $fileName = time() . '-' . mb_strtolower($fileName);

                    $filePath = './images/books/';

                    if (!file_exists($filePath)) {
                        mkdir($filePath, 0777, true);
                    }
                    $filePath = $filePath . $fileName;
                    move_uploaded_file($temp, $filePath);
                    if (file_exists($filePath)) {
                        $update = Tbl_libro::model()->updateAll(
                            [
                                'Url' =>  str_replace('./', '/', $filePath),
                            ],
                            'RowId=:id',
                            [':id' => $add->RowId]
                        );
                    }
                }
            } catch (\Throwable $th) {
            }

            foreach ($create_book_program[0] as $key => $idProgram) {
                $createProgram = new Tbl_libro_programa();
                $create = $createProgram->createBookProgram($add->RowId, $idProgram);
                if ($create['Status'] != '200') {
                    return $create;
                }
            }

            foreach ($create_book_subject[0] as $key => $idSubject) {
                $createSubject = new Tbl_libro_materia();
                $create = $createSubject->createBookSubject($add->RowId, $idSubject);
                if ($create['Status'] != '200') {
                    return $create;
                }
            }

            foreach ($create_book_category[0] as $key => $idCategory) {
                $createCategory = new Tbl_libro_categoria();
                $create = $createCategory->createBookCategory($add->RowId, $idCategory);
                if ($create['Status'] != '200') {
                    return $create;
                }
            }

            foreach ($create_book_language[0] as $key => $idLanguage) {
                $createLanguage = new Tbl_libro_idioma();
                $create = $createLanguage->createBookLanguage($add->RowId, $idLanguage);
                if ($create['Status'] != '200') {
                    return $create;
                }
            }

            return Responses::getOk('Libro creado correctamente');
        } catch (\Throwable $th) {
            return Responses::getError();
        }
    }


    public function editBook($request_data, $request_dataFile)
    {
        if (!isset($request_data)) {
            return Responses::getNoContent();
        }

        foreach ($request_data as $key => $value) {
            switch ($key) {
                case 'id-book-edit':
                    $id_book_edit                   =   Encrypt::decryption($value);
                    break;
                case 'edit-book-title':
                    $edit_book_title              =   $value;
                    break;
                case 'edit-book-author':
                    $edit_book_author             =   $value;
                    break;
                case 'edit-book-publisher':
                    $edit_book_publisher          =   $value;
                    break;
                case 'edit-book-description':
                    $edit_book_description        =   $value;
                    break;
                case 'edit-book-content':
                    $edit_book_content            =   $value;
                    break;
                case 'edit-book-first-edition':
                    $edit_book_first_edition      =   $value;
                    break;
                case 'edit-book-first-mention':
                    $edit_book_first_mention      =   $value;
                    break;
                case 'edit-book-series':
                    $edit_book_series             =   $value;
                    break;
                case 'edit-book-status':
                    $edit_book_status             =   $value;
                    break;
                case 'edit-book-program':
                    $edit_book_program[]          =   $value;
                    break;
                case 'edit-book-subject':
                    $edit_book_subject[]          =   $value;
                    break;
                case 'edit-book-category':
                    $edit_book_category[]         =   $value;
                    break;
                case 'edit-book-language':
                    $edit_book_language[]         =   $value;
                    break;
            }
        }
        try {
            $exists = Tbl_libro::model()->find('RowId=:id', [':id' => $id_book_edit]);

            if (!$exists) {
                return Responses::getNoContent();
            }

            $update = Tbl_libro::model()->updateAll(
                [
                    'Descripcion'           =>  $edit_book_description,
                    'Contenido'             =>  $edit_book_content,
                    'Titulo'                =>  $edit_book_title,
                    'Autor'                 =>  $edit_book_author,
                    'PrimeraEdicion'        =>  $edit_book_first_edition,
                    'MencionPrimera'        =>  $edit_book_first_mention,
                    'Serie'                 =>  $edit_book_series,
                    'Editorial'             =>  $edit_book_publisher,
                    'RowIdEstado'           =>  $edit_book_status,
                    'RowIdUsuarioEditor'    =>  Yii::app()->user->rowId,
                    'FechaEdicion'          =>  date('Y-m-d H:i:s')
                ],
                'RowId=:id',
                [':id' => $exists->RowId]
            );

            if (!$update) {
                return Responses::getErrorValidation('Error en el proceso de actualización');
            }
            try {
                if (isset($request_dataFile['edit-book-upload-file']) && $request_dataFile['edit-book-upload-file']['name']) {
                    $fichero    =   $request_dataFile['edit-book-upload-file'];
                    $tipo       =   $request_dataFile['edit-book-upload-file']['type'];
                    $tamano     =   $request_dataFile['edit-book-upload-file']['size'];
                    $temp       =   $request_dataFile['edit-book-upload-file']['tmp_name'];
                    $ext        =   pathinfo($fichero['name'], PATHINFO_EXTENSION);
                    $porcentaje = 0.5;
                    list($ancho, $alto) = getimagesize($temp);
                    $nuevo_ancho = 250; //$ancho * $porcentaje;
                    $nuevo_alto = 350; //$alto * $porcentaje;
                    //Redimensionar
                    $imagen_p = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);

                    if ($ext == 'png') {
                        $imagen = imagecreatefrompng($temp);
                    } else if ($ext == 'jpeg') {
                        $imagen = imagecreatefromjpeg($temp);
                    } else if ($ext == 'jpg') {
                        $imagen = imagecreatefromjpeg($temp);
                    }
                    imagecopyresampled($imagen_p, $imagen, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho, $alto);
                    /* Sobreescribimos la imagen original con la reescalada */
                    imagejpeg($imagen_p, $temp);
                    /* Actualizo el tamaño al que tiene la imagen reescalada */
                    $request_dataFile['edit-book-upload-file']['size'] = filesize($temp);

                    $fileName = preg_replace('/\s+/', '-', $fichero['name']);
                    $fileName = preg_replace('/[^A-Za-z0-9.\-]/', '', $fileName);
                    $fileName = time() . '-' . mb_strtolower($fileName);

                    $filePath = './images/books/';

                    chown('.' . $exists->Url, 666);
                    if (!unlink('.' . $exists->Url)) {
                        return Responses::getErrorValidation('Error eliminado el archivo anterior');
                    }

                    if (!file_exists($filePath)) {
                        mkdir($filePath, 0777, true);
                    }
                    $filePath = $filePath . $fileName;
                    move_uploaded_file($temp, $filePath);
                    if (file_exists($filePath)) {
                        $update = Tbl_libro::model()->updateAll(
                            [
                                'Url' =>  str_replace('./', '/', $filePath),
                            ],
                            'RowId=:id',
                            [':id' => $exists->RowId]
                        );
                    }
                }
            } catch (\Throwable $th) {
            }

            $update = Tbl_libro_programa::model()->updateAll(
                [
                    'Estado'                =>  0,
                    'RowIdUsuarioEditor'    =>  Yii::app()->user->rowId,
                    'FechaEdicion'          =>  date('Y-m-d H:i:s'),
                ],
                'RowIdLibro=:_RowIdLibro',
                [':_RowIdLibro' => $exists->RowId]
            );

            foreach ($edit_book_program[0] as $key => $idProgram) {
                $editProgram = new Tbl_libro_programa();
                $edit = $editProgram->updateBookProgram($exists->RowId, $idProgram);
                if ($edit['Status'] != '200') {
                    return $edit;
                }
            }
            $update = Tbl_libro_materia::model()->updateAll(
                [
                    'Estado'                =>  0,
                    'RowIdUsuarioEditor'    =>  Yii::app()->user->rowId,
                    'FechaEdicion'          =>  date('Y-m-d H:i:s'),
                ],
                'RowIdLibro=:_RowIdLibro',
                [':_RowIdLibro' => $exists->RowId]
            );

            foreach ($edit_book_subject[0] as $key => $idSubject) {
                $editSubject = new Tbl_libro_materia();
                $edit = $editSubject->updateBookSubject($exists->RowId, $idSubject);
                if ($edit['Status'] != '200') {
                    return $edit;
                }
            }

            $update = Tbl_libro_categoria::model()->updateAll(
                [
                    'Estado'                =>  0,
                    'RowIdUsuarioEditor'    =>  Yii::app()->user->rowId,
                    'FechaEdicion'          =>  date('Y-m-d H:i:s'),
                ],
                'RowIdLibro=:_RowIdLibro',
                [':_RowIdLibro' => $exists->RowId]
            );

            foreach ($edit_book_category[0] as $key => $idCategory) {
                $editCategory = new Tbl_libro_categoria();
                $edit = $editCategory->updateBookCategory($exists->RowId, $idCategory);
                if ($edit['Status'] != '200') {
                    return $edit;
                }
            }

            $update = Tbl_libro_idioma::model()->updateAll(
                [
                    'Estado'                =>  0,
                    'RowIdUsuarioEditor'    =>  Yii::app()->user->rowId,
                    'FechaEdicion'          =>  date('Y-m-d H:i:s'),
                ],
                'RowIdLibro=:_RowIdLibro',
                [':_RowIdLibro' => $exists->RowId]
            );

            foreach ($edit_book_language[0] as $key => $idLanguage) {
                $editLanguage = new Tbl_libro_idioma();
                $edit = $editLanguage->updateBookLanguage($exists->RowId, $idLanguage);
                if ($edit['Status'] != '200') {
                    return $edit;
                }
            }

            return Responses::getOk('Libro creado correctamente');
        } catch (\Throwable $th) {
            return Responses::getError();
        }
    }

    public function getBookById($id)
    {
        $exists = Yii::app()->db->createCommand("select Distinct
                                                            RowId
                                                            ,Descripcion
                                                            ,Contenido
                                                            ,Titulo
                                                            ,Autor
                                                            ,PrimeraEdicion
                                                            ,MencionPrimera
                                                            ,Serie
                                                            ,Editorial
                                                            ,Url
                                                            ,RowIdEstado
                                                            ,FechaCreacion
                                                            ,Creador
                                                        from
                                                                VW_LISTA_LIBROS
                                                        where 
                                                        RowId='" . $id . "'")
            ->queryRow();
        if (!$exists) {
            return Responses::getNoContent();
        }
        return Responses::getOk([
            'RowIdHash'         => Encrypt::encryption($exists['RowId']),
            'RowId'             =>  $exists['RowId'],
            'Descripcion'       =>  $exists['Descripcion'],
            'Contenido'         =>  $exists['Contenido'],
            'Titulo'            =>  $exists['Titulo'],
            'Autor'             =>  $exists['Autor'],
            'PrimeraEdicion'    =>  $exists['PrimeraEdicion'],
            'MencionPrimera'    =>  $exists['MencionPrimera'],
            'Serie'             =>  $exists['Serie'],
            'Editorial'         =>  $exists['Editorial'],
            'Url'               =>  $exists['Url'],
            'Estado'            =>  $exists['RowIdEstado'],
            'FechaCreacion'     =>  $exists['FechaCreacion'],
            'Creador'           =>  $exists['Creador'],
            'programa'          =>  array_column(
                Tbl_libro_programa::model()->findAll(
                    [
                        'select' => 'RowIdPrograma',
                        'distinct' => 'true',
                        'condition' => 'RowIdLibro=:id and Estado=:status',
                        'params' => [':id' => $exists['RowId'], ':status' => 1],
                    ]
                ),
                'RowIdPrograma'
            ),
            'materia'           =>  array_column(
                Tbl_libro_materia::model()->findAll(
                    [
                        'select' => 'RowIdMateria',
                        'distinct' => 'true',
                        'condition' => 'RowIdLibro=:id and Estado=:status',
                        'params' => [':id' => $exists['RowId'], ':status' => 1],
                    ]
                ),
                'RowIdMateria'
            ),
            'categoria'         =>  array_column(
                Tbl_libro_categoria::model()->findAll(
                    [
                        'select' => 'RowIdCategoria',
                        'distinct' => 'true',
                        'condition' => 'RowIdLibro=:id and Estado=:status',
                        'params' => [':id' => $exists['RowId'], ':status' => 1],
                    ]
                ),
                'RowIdCategoria'
            ),
            'idioma' =>  array_column(
                Tbl_libro_idioma::model()->findAll(
                    [
                        'select' => 'RowIdIdioma',
                        'distinct' => 'true',
                        'condition' => 'RowIdLibro=:id and Estado=:status',
                        'params' => [':id' => $exists['RowId'], ':status' => 1],
                    ]
                ),
                'RowIdIdioma'
            )
        ]);
    }
    public function getTextBook($data)
    {
        $exists = Tbl_libro::model()->find('RowId=:id', [':id' => $data['id']]);

        if (!$exists) {
            return Responses::getNoContent();
        }
        $getData = Yii::app()->db->createCommand(
            "DECLARE @rowIdOut nvarchar(250)
            DECLARE @programaOut nvarchar(max)
            DECLARE @materiaOut nvarchar(max)
            DECLARE @categoriaOut nvarchar(max)
            DECLARE @idiomasOut nvarchar(max)
            DECLARE @result char(3)
            EXECUTE SP_LIBRO_DEPENDENCIAS '" . $data['id'] . "', @rowIdOut OUTPUT,@programaOut OUTPUT,@materiaOut OUTPUT,@categoriaOut OUTPUT,@idiomasOut OUTPUT, @result OUTPUT
            SELECT 
                @rowIdOut as RowId
                ,@programaOut as Programas
                ,@materiaOut as Materias
                ,@categoriaOut as Categorias
                ,@idiomasOut as Idiomas
                ,@result as Status"
        )->queryRow();
        if ($getData['Status'] == '200') {
            $program    =   $getData['Programas'];
            $category   =   $getData['Categorias'];
            $subject    =   $getData['Materias'];
            $languaje   =   $getData['Idiomas'];
        } else {
            $program    =   '';
            $category   =   '';
            $subject    =   '';
            $languaje   =   '';
        }

        switch ($data['name']) {
            case 'Descripcion':
                return Responses::getOk(['text' => $exists->Descripcion]);
                break;
            case 'Contenido':
                return Responses::getOk(['text' => $exists->Contenido]);
                break;
            case 'PrimeraEdicion':
                return Responses::getOk(['text' => $exists->PrimeraEdicion]);
                break;
            case 'MencionPrimera':
                return Responses::getOk(['text' => $exists->MencionPrimera]);
                break;
            case 'Serie':
                return Responses::getOk(['text' => $exists->Serie]);
                break;
            case 'Editorial':
                return Responses::getOk(['text' => $exists->Editorial]);
                break;
            case 'FechaCreacion':
                return Responses::getOk(['text' => $exists->FechaCreacion]);
                break;
            case 'Creador':
                return Responses::getOk(['text' => $exists->Creador]);
                break;
            case 'Programas':
                return Responses::getOk(['text' => $program]);
                break;
            case 'Materias':
                return Responses::getOk(['text' => $subject]);
                break;
            case 'Categoria':
                return Responses::getOk(['text' => $category]);
                break;
            case 'Idiomas':
                return Responses::getOk(['text' => $languaje]);
                break;
        }
    }
    public function getViewBookById($id)
    {
        $exist = Yii::app()->db->createCommand("select Distinct
                                                        RowId
                                                        ,Descripcion
                                                        ,Contenido
                                                        ,Titulo
                                                        ,Autor
                                                        ,PrimeraEdicion
                                                        ,MencionPrimera
                                                        ,Serie
                                                        ,Editorial
                                                        ,Url
                                                        ,RowIdEstado
                                                        ,EstadoNombre
                                                        ,FechaCreacion
                                                        ,Creador
                                                    from
                                                        VW_LISTA_LIBROS
                                                    where 
                                                        RowId = '" . $id . "'")->queryRow();

        if ($exist) {
            $img = $description = '';

            $img = $description = '';
            $getData = Yii::app()->db->createCommand(
                "DECLARE @rowIdOut nvarchar(250)
                    DECLARE @programaOut nvarchar(max)
                    DECLARE @materiaOut nvarchar(max)
                    DECLARE @categoriaOut nvarchar(max)
                    DECLARE @idiomasOut nvarchar(max)
                    DECLARE @result char(3)
                    EXECUTE SP_LIBRO_DEPENDENCIAS '" . $exist['RowId'] . "', @rowIdOut OUTPUT,@programaOut OUTPUT,@materiaOut OUTPUT,@categoriaOut OUTPUT,@idiomasOut OUTPUT, @result OUTPUT
                    SELECT 
                        @rowIdOut as RowId
                        ,@programaOut as Programas
                        ,@materiaOut as Materias
                        ,@categoriaOut as Categorias
                        ,@idiomasOut as Idiomas
                        ,@result as Status"
            )->queryRow();
            if ($getData['Status'] == '200') {
                $program    =   $getData['Programas'];
                $category   =   $getData['Categorias'];
                $subject    =   $getData['Materias'];
                $languaje   =   $getData['Idiomas'];
            } else {
                $program    =   '';
                $category   =   '';
                $subject    =   '';
                $languaje   =   '';
            }

            $fecha = new DateTime($exist['FechaCreacion']);
            $fechaFormateada = date_format($fecha, 'Y-m-d h:i A');
            $description .= '
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3">                            
                            <img class="card-img-top img-fluid mx-auto" style="max-width: 282px; max-height: 408px;" src="' . Yii::app()->request->baseUrl . ($exist['Url'] == 'Url-no-exists' ? '/images/sin-imagen.png' : $exist['Url']) . '" alt="' . $exist['Titulo'] . '">
                            <div class="card-body">
                                <h5 class="card-title">Titulo: ' . $exist['Titulo'] . '</h5>
                                <p class="card-text"><strong>Autor: </strong>' . (strlen($exist['Autor']) > 200 ? substr($exist['Autor'], 0, 200) . '...<a href="javascript:void(0);" onclick="showText(' . "'Autor','" . $exist['RowId'] . "'" . ')" type="button" >[leer más]</a>' : $exist['Autor']) . '</p>
                                <p class="card-text"><strong>Descripción: </strong>' . (strlen($exist['Descripcion']) > 200 ? substr($exist['Descripcion'], 0, 200) . '...<a href="javascript:void(0);" onclick="showText(' . "'Descripcion','" . $exist['RowId'] . "'" . ')" type="button" >[leer más]</a>' : $exist['Descripcion']) . '</p>
                                <p class="card-text"><strong>Contenido: </strong>' . (strlen($exist['Contenido']) > 200 ? substr($exist['Contenido'], 0, 200) . '...<a href="javascript:void(0);" onclick="showText(' . "'Contenido','" . $exist['RowId'] . "'" . ')" type="button" >[leer más]</a>' : $exist['Contenido']) . '</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="card-text"><strong>Primera edición: </strong>' . (strlen($exist['PrimeraEdicion']) > 200 ? substr($exist['PrimeraEdicion'], 0, 200) . '...<a href="javascript:void(0);" onclick="showText(' . "'PrimeraEdicion','" . $exist['RowId'] . "'" . ')" type="button" >[leer más]</a>' : $exist['PrimeraEdicion']) . '</p>
                                <p class="card-text"><strong>Mención primera: </strong>' . (strlen($exist['MencionPrimera']) > 200 ? substr($exist['MencionPrimera'], 0, 200) . '...<a href="javascript:void(0);" onclick="showText(' . "'MencionPrimera','" . $exist['RowId'] . "'" . ')" type="button" >[leer más]</a>' : $exist['MencionPrimera']) . '</p>
                                <p class="card-text"><strong>Serie: </strong>' . (strlen($exist['Serie']) > 200 ? substr($exist['Serie'], 0, 200) . '...<a href="javascript:void(0);" onclick="showText(' . "'Serie','" . $exist['RowId'] . "'" . ')" type="button" >[leer más]</a>' : $exist['Serie']) . '</p>
                                <p class="card-text"><strong>Editorial: </strong>' . (strlen($exist['Editorial']) > 200 ? substr($exist['Editorial'], 0, 200) . '...<a href="javascript:void(0);" onclick="showText(' . "'Editorial','" . $exist['RowId'] . "'" . ')" type="button" >[leer más]</a>' : $exist['Editorial']) . '</p>
                                <p class="card-text"><strong>Fecha de registro: </strong>' . (strlen($fechaFormateada) > 200 ? substr($fechaFormateada, 0, 200) . '...<a href="javascript:void(0);" onclick="showText(' . "'FechaCreacion','" . $exist['RowId'] . "'" . ')" type="button" >[leer más]</a>' : $exist['FechaCreacion']) . '</p>
                                <p class="card-text"><strong>Creador de registro: </strong>' . (strlen($exist['Creador']) > 200 ? substr($exist['Creador'], 0, 200) . '...<a href="javascript:void(0);" onclick="showText(' . "'Creador','" . $exist['RowId'] . "'" . ')" type="button" >[leer más]</a>' : $exist['Creador']) . '</p>
                                <p class="card-text"><strong>Programas: </strong>' . (strlen($program) > 80 ? substr($program, 0, 80) . '...<a href="javascript:void(0);" onclick="showText(' . "'Programas','" . $exist['RowId'] . "'" . ')" type="button" >[leer más]</a>' : $program) . '</p>
                                <p class="card-text"><strong>Materias: </strong>' . (strlen($subject) > 80 ? substr($subject, 0, 80) . '...<a href="javascript:void(0);" onclick="showText(' . "'Materias','" . $exist['RowId'] . "'" . ')" type="button" >[leer más]</a>' : $subject) . '</p>
                                <p class="card-text"><strong>Categoria: </strong>' . (strlen($category) > 80 ? substr($category, 0, 80) . '...<a href="javascript:void(0);" onclick="showText(' . "'Categoria','" . $exist['RowId'] . "'" . ')" type="button" >[leer más]</a>' : $category) . '</p>
                                <p class="card-text"><strong>Idiomas: </strong>' . (strlen($languaje) > 80 ? substr($languaje, 0, 80) . '...<a href="javascript:void(0);" onclick="showText(' . "'Idiomas','" . $exist['RowId'] . "'" . ')" type="button" >[leer más]</a>' : $languaje) . '</p>
                            </div>';

            $description .= '</div></div></div></div>';

            $rsv = [
                'Status' => '400',
                'btn'   => ''
            ];
            if ($exist['RowIdEstado'] == 'FF9EBAF1-A4CD-4143-8095-9CB96A4F2314') {
                $rsv['Status'] = 200;
                $rsv['btn'] = '<button type="button" id="btn-id-book-reserve" class="btn btn-success" onclick="reserveBookModal(' . "'" . Encrypt::encryption($exist['RowId']) . "'" . ')">RESERVAR</button>';
            }

            return [
                'Status'    =>  '200',
                'Message'   =>  '¡El proceso se ha ejecutado correctamente!',
                'html'      =>  $description,
                'rsv'       =>  $rsv

            ];
        }
        return [
            'Status' => '204',
            'Message' => '¡No existen registros para la petición realizada!'
        ];
    }
    public function processManageBook($idBook, $method)
    {
        $exists = Tbl_libro::model()->find('RowId=:id', [':id' => $idBook]);

        if (!$exists) {
            return Responses::getNoContent();
        }

        switch ($method) {
            case "deliver":
                return $this->deliver($idBook, $method);
                break;
            case "cancel":
                return $this->cancelProcess($idBook, $method);
                break;
            case "receive":
                return $this->receive($idBook, $method);
                break;
        }
    }
    //Entregar
    public function deliver($idBook, $method)
    {
        $update = Tbl_libro::model()->updateAll(
            [
                'RowIdEstado'           =>  '6AD1D9A0-53DC-4709-9B54-F66BA6E433FE',
                'RowIdUsuarioEditor'    =>  Yii::app()->user->rowId,
                'FechaEdicion'          =>  date('Y-m-d H:i:s')
            ],
            'RowId=:id',
            [':id' => $idBook]
        );
        if (!$update) {
            return Responses::getErrorValidation('La solicitud no fue procesada.');
        }

        $updateDeliver = Tbl_libro_prestamo::model()->updateAll(
            [
                'RowIdEstadoPrestamo'   =>  'FDE346B2-EECF-4901-A3E1-E7BD503FC571',
                'Fecha_recogida'        =>  date('Y-m-d H:i:s'),
                'RowIdUsuarioEditor'    =>  Yii::app()->user->rowId,
                'FechaEdicion'          =>  date('Y-m-d H:i:s')
            ],
            'RowIdLibro=:_RowIdLibro and RowIdEstadoPrestamo=:_RowIdEstadoPrestamo',
            [
                ':_RowIdLibro' => $idBook,
                ':_RowIdEstadoPrestamo' => '3BEB12E5-3632-4A11-8598-1C66F379AAA9'
            ]
        );

        if (!$updateDeliver) {
            return Responses::getErrorValidation('Se actualizó en el inventario, pero no se actualizo el estado de prestamo.');
        }
        return Responses::getOk('Procesado correctamente.');
    }
    //Cancelar
    public function cancelProcess($idBook, $method)
    {
        $update = Tbl_libro::model()->updateAll(
            [
                'RowIdEstado'           =>  'FF9EBAF1-A4CD-4143-8095-9CB96A4F2314',
                'RowIdUsuarioEditor'    =>  Yii::app()->user->rowId,
                'FechaEdicion'          =>  date('Y-m-d H:i:s')
            ],
            'RowId=:id',
            [':id' => $idBook]
        );
        if (!$update) {
            return Responses::getErrorValidation('La solicitud no fue procesada.');
        }

        $cancelProcess = Tbl_libro_prestamo::model()->updateAll(
            [
                'RowIdEstadoPrestamo'   =>  '1825B4F6-DBA0-4BCB-AEF2-100D2E193478',
                'RowIdUsuarioEditor'    =>  Yii::app()->user->rowId,
                'FechaEdicion'          =>  date('Y-m-d H:i:s')
            ],
            'RowIdLibro=:_RowIdLibro and RowIdEstadoPrestamo=:_RowIdEstadoPrestamo',
            [
                ':_RowIdLibro' => $idBook,
                ':_RowIdEstadoPrestamo' => '3BEB12E5-3632-4A11-8598-1C66F379AAA9'
            ]
        );

        if (!$cancelProcess) {
            return Responses::getErrorValidation('Se actualizó en el inventario, pero no se actualizo el estado de prestamo.');
        }
        return Responses::getOk('Procesado correctamente.');
    }
    //Recibir
    public function receive($idBook, $method)
    {
        $update = Tbl_libro::model()->updateAll(
            [
                'RowIdEstado'           =>  'FF9EBAF1-A4CD-4143-8095-9CB96A4F2314',
                'RowIdUsuarioEditor'    =>  Yii::app()->user->rowId,
                'FechaEdicion'          =>  date('Y-m-d H:i:s')
            ],
            'RowId=:id',
            [':id' => $idBook]
        );
        if (!$update) {
            return Responses::getErrorValidation('La solicitud no fue procesada.');
        }
        $receive = Tbl_libro_prestamo::model()->updateAll(
            [
                'RowIdEstadoPrestamo'   =>  '98FBFEF8-0BC0-4D3F-82C8-D2B8EAA50029',
                'Fecha_devolvio'        =>  date('Y-m-d H:i:s'),
                'RowIdUsuarioEditor'    =>  Yii::app()->user->rowId,
                'FechaEdicion'          =>  date('Y-m-d H:i:s')
            ],
            'RowIdLibro=:_RowIdLibro and RowIdEstadoPrestamo=:_RowIdEstadoPrestamo OR RowIdEstadoPrestamo=:_RowIdEstadoPrestamo2',
            [
                ':_RowIdLibro' => $idBook,
                ':_RowIdEstadoPrestamo' => 'FDE346B2-EECF-4901-A3E1-E7BD503FC571',
                ':_RowIdEstadoPrestamo2' => '186654F1-CD2D-4A57-9EFB-B791C7D8D7BA'
            ]
        );

        if (!$receive) {
            return Responses::getErrorValidation('Se actualizó en el inventario, pero no se actualizo el estado de prestamo.');
        }
        return Responses::getOk('Procesado correctamente.');
    }
    public function updateReservations()
    {
        $updatePriceList = Yii::app()->db->createCommand("exec SP_ActualizarLibrosVencidos")->queryRow();
        if ($updatePriceList['Resultado'] == 1) {
            return 'procesado';
        } else {
            return 'error';
        }
    }
}
