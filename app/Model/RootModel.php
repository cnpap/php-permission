<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RootModel extends Model
{
    protected $primaryKey = 'code';

    protected $keyType = 'string';
}