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
            $add->Estado                    =   1;
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
                    'Estado'                =>  $edit_book_status,
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
                                                            ,Estado
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
            'Estado'            =>  $exists['Estado'],
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
}
