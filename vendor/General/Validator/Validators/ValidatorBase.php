<?php

namespace Vendor\General\Validator;

class ValidatorBase {
    private $error = '';

    public function validate($field, $value) : bool {}

    public function getError() {
        return $this->error;
    }
}