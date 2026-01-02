<?php

namespace Vendor\General\Validator;

use Vendor\Foundation\Request;
use Vendor\General\Session;

class Validator {
    public static function validate(Request $request, array $data) : bool{
        $errors = [];
        foreach ($data as $field => $rulesString) {
            $rules = explode('|', $rulesString);
            if((!$request->has($field) || $request->getAny($field) === '' || $request->getAny($field) === null) && !in_array('required', $rules)) {
                continue;
            }
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

    public static function validateData(array $data, array $rules) : bool{
        $errors = [];
        foreach ($rules as $field => $rulesString) {
            $rules = explode('|', $rulesString);
            if((!isset($data[$field]) || $data[$field] === '' || $data[$field] === null) && !in_array('required', $rules)) {
                continue;
            }
            foreach ($rules as $rule) {
                $validator = ValidatorFactory::create($rule);
                if(!$validator->validate($field, $data[$field])) {
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