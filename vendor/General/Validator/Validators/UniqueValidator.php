<?php

namespace Vendor\General\Validator;

use Vendor\Facades\DB;

class UniqueValidator extends ValidatorBase {
    private string $tableName;

    public function __construct(string $tableName) {
        $this->tableName = $tableName;
    }

    public function validate($field, $value) : bool {
        $count = DB::table($this->tableName)->where([$field => $value])->num_rows;
        if ($count > 0) {
            $this->error = "The field $field must be unique. The value '$value' already exists.";
            return false;
        }
        return true;
    }
}