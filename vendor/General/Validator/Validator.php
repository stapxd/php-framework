<?php

namespace Vendor\General\Validator;

use Vendor\Foundation\Request;
use Vendor\General\Session;

class Validator {
    public static function validate(Request $request, array $data) {
        $errors = [];
        foreach ($data as $field => $rulesString) {
            $rules = explode('|', $rulesString);
            foreach ($rules as $rule) {
                $validator = ValidatorFactory::create($rule);
                if(!$validator->validate($field, $data[$field] ?? null)) {
                    $errors[] = $validator->getError();
                }
            }
        }
        Session::flash('errors', $errors);
    }
}