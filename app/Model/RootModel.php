<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    use SoftDeletes;

    protected $guarded = [];

    protected $primaryKey = 'code';

    protected $keyType = 'string';
}