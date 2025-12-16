<?php

namespace Vendor\General\Validator;

class MinValidator extends ValidatorBase {
    private int $minValue;

    public function __construct(int $minValue) {
        $this->minValue = $minValue;
    }

    public function validate($field, $value) : bool {
        $value = floatval($value);
        if ($value < $this->minValue) {
            $this->error = "The field $field must be greater than or equal to {$this->minValue}.";
            return false;
        }
        return true;
    }
}