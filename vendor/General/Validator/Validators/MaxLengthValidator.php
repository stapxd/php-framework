<?php

namespace Vendor\General\Validator;

class MaxLengthValidator extends ValidatorBase {
    private int $maxLength;

    public function __construct(int $maxLength) {
        $this->maxLength = $maxLength;
    }

    public function validate($field, $value) : bool {
        if (strlen($value) > $this->maxLength) {
            $this->error = "The field $field must be less than {$this->maxLength} characters long.";
            return false;
        }
        return true;
    }
}