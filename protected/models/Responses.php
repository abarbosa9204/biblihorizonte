<?php

class Responses
{
    public static function getOk($arr = [], $message = null)
    {
        return [
            'Status' => '200',
            'Message' => ($message != null ? $message : '¡El proceso se ha ejecutado correctamente!'),
            'data' => $arr
        ];
    }
    public static function getCreated($arr = [])
    {
        return [
            'Status' => '201',
            'Message' => '¡Registros procesados correctamente!',
            'data' => $arr
        ];
    }
    public static function getNoContent($message = null)
    {
        return [
            'Status' => '204',
            'Message' => ($message != null ? $message : '¡No existen registros para la petición realizada!')
        ];
    }
    public static function getError()
    {
        return [
            'Status' => '500',
            'Message' => '¡Ha ocurrido un error inesperado!'
        ];
    }
    public static function getErrorValidation($message = null)
    {
        return [
            'Status' => '400',
            'Message' => ($message != null ? $message : 'Bad Request')
        ];
    }
    public static function getErrorsValidation($arr = [])
    {
        return [
            'Status' => '400',
            'Message' => '¡Ha ocurrido un error inesperado!',
            'Errors' => $arr
        ];
    }
    public static function getErrorCustom($message = null)
    {
        return [
            'Status' => '400',
            'Message' => ($message != null ? $message : '¡Ha ocurrido un error inesperado!'),
        ];
    }
}
