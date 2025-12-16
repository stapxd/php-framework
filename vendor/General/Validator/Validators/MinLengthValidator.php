<?php

namespace Vendor\General\Validator\Validators;

class MinLengthValidator extends ValidatorBase {
    private int $minLength;

    public function __construct(int $minLength) {
        $this->minLength = $minLength;
    }

    public function validate($field, $value) : bool {
        if (strlen($value) < $this->minLength) {
            $this->error = "The field $field must be at least {$this->minLength} characters long.";
            return false;
        }
        return true;
    }
}