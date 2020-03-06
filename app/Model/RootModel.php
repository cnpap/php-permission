<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $code
 * 
 * @property int    $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class RootModel extends Model
{
    protected $primaryKey = 'code';

    protected $keyType = 'string';
}