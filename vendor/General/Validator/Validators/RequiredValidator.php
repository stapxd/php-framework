<?php

namespace Vendor\General\Validator\Validators;

class RequiredValidator extends ValidatorBase {
    public function validate($field, $value) : bool {
        if (!isset($value) || empty($value)) {
            $this->error = "The field $field is required.";
            return false;
        }
        return true;
    }
}