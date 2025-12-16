<?php

namespace Vendor\General\Validator;

class RegexpValidator extends ValidatorBase {
    private string $regexp;

    public function __construct(string $regexp) {
        $this->regexp = $regexp;
    }

    public function validate($field, $value) : bool {
        if (!preg_match($this->regexp, $value)) {
            $this->error = "The field $field does not match the required format.";
            return false;
        }
        return true;
    }
}