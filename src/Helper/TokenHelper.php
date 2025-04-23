<?php

namespace App\Helper;

class TokenHelper
{
    public static function generarCodigo(int $longitud = 6): string
    {
        return str_pad(strval(random_int(0, pow(10, $longitud) - 1)), $longitud, '0', STR_PAD_LEFT);
    }
}
