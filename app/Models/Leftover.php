<?php

namespace App\Models;

use App\Models\Model;

class Leftover extends Model
{
    public $table = 'leftovers';

    public function leftoverComments()
    {
        return $this->hasMany(LeftoverComment::class, 'leftover_id', 'id');
    }

    public function leftoverSaves()
    {
        return $this->hasMany(LeftoverSave::class, 'leftover_id', 'id');
    }

    public function leftoverRequest()
    {
        return $this->hasMany(leftoverRequest::class, 'leftover_id', 'id');
    }
}
