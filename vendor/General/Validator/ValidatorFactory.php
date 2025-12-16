<?php

namespace Vendor\General\Validator;

use Exception;

class ValidatorFactory {
    public static function create(string $rule): ValidatorBase {
        switch ($rule) {
            case 'required':
                return new RequiredValidator();
            case (str_starts_with($rule, 'max_length:')):
                $length = (int)explode(':', $rule)[1];
                return new MaxLengthValidator($length);
            case (str_starts_with($rule, 'min_length:')):
                $length = (int)explode(':', $rule)[1];
                return new MinLengthValidator($length);
            case (str_starts_with($rule, 'min:')):
                $min = (int)explode(':', $rule)[1];
                return new MinValidator($min);
            case (str_starts_with($rule, 'max:')):
                $max = (int)explode(':', $rule)[1];
                return new MaxValidator($max);
            case (str_starts_with($rule, 'regexp:')):
                $regexp = explode(':', $rule, 2)[1];
                return new RegexpValidator($regexp);
            case (str_starts_with($rule, 'unique:')):
                $tableName = explode(':', $rule)[1];
                return new UniqueValidator($tableName);
            default:
                throw new Exception("ValidatorFactory::create : Unknown validation rule $rule");
        }
    }
}