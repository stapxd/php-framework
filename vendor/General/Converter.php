<?php

namespace Vendor\General;

use Exception;

class Converter {
    public static function convertToType(string $type, $param) {
        return match ($type) {
            'int'    => (int)$param,
            'float'  => (float)$param,
            'string' => (string)$param,
            'bool'   => (bool)$param,
            default  => throw new Exception("Converter::convertToType : $type is not supported!")
        };
    }
}