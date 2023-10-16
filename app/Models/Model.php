<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;

abstract class Model extends EloquentModel
{
    use HasFactory;

    protected $guarded = [];

    /**
     * after laravel 7
     * default dates serialized using the new format will appear like: 2019-12-02T20:01:00.283041Z.
     * if we want to keep previous behavior, we need override it by this method.
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        // return Carbon::parse($date, 'UTC')->setTimezone('Asia/Taipei')->format('Y-m-d H:i:s');
        return $date->format('Y-m-d H:i:s');
    }
}
