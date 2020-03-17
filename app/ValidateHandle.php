<?php

namespace App;

use Illuminate\Support\Facades\DB;

class ValidateHandle
{
    function uniqueCheck($data, $rules)
    {
        return is_string($data) && $this->findDatabase($data, $rules) !== true;
    }

    function existCheck($data, $rules)
    {
        return is_string($data) && $this->findDatabase($data, $rules);
    }

    private function findDatabase($data, $rules)
    {
        list($table, $column) = explode('.', $rules);
        $result = DB::table($table)->where($column, $data)->count();
        
        return $result > 0;
    }
}