<?php

namespace Vendor\General\Validator\Validators;

class MaxValidator extends ValidatorBase {
    private int $maxValue;

    public function __construct(int $maxValue) {
        $this->maxValue = $maxValue;
    }

    public function validate($field, $value) : bool {
        $value = floatval($value);
        if ($value > $this->maxValue) {
            $this->error = "The field $field must be less than or equal to {$this->maxValue}.";
            return false;
        }
        return true;
    }
}