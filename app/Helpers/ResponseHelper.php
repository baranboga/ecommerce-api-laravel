<?php

namespace App\Helpers;

class ResponseHelper
{
    public static function success($data = null, string $message = 'İşlem başarılı', int $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'errors' => []
        ], $code);
    }

    public static function error(string $message = 'Bir hata oluştu', array $errors = [], int $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null,
            'errors' => $errors
        ], $code);
    }

    public static function validationError(array $errors, string $message = 'Validasyon hatası')
    {
        return self::error($message, $errors, 422);
    }

    public static function unauthorized(string $message = 'Yetkisiz erişim')
    {
        return self::error($message, [], 401);
    }

    public static function notFound(string $message = 'Kayıt bulunamadı')
    {
        return self::error($message, [], 404);
    }
}

