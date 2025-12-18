<?php

namespace Vendor\General\Validator\Validators;

class ValidatorBase {
    protected $error = '';

    public function validate($field, $value) : bool {}

    public function getError() {
        return $this->error;
    }
}