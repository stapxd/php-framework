<?php

namespace Vendor\General\Validator;

use Vendor\Foundation\Request;
use Vendor\General\Session;

class Validator {
    public static function validate(Request $request, array $data) : bool{
        $errors = [];
        foreach ($data as $field => $rulesString) {
            $rules = explode('|', $rulesString);
            foreach ($rules as $rule) {
                $validator = ValidatorFactory::create($rule);
                if(!$validator->validate($field, $request->getAny($field))) {
                    $errors[] = $validator->getError();
                    break;
                }
            }
        }
        Session::flash('errors', $errors);

        if(count($errors) > 0){
            return false;
        }
        else {
            return true;
        }
    }
}