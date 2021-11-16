<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'logs';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'data',
        'created_at',
    ];
}
