<?php

namespace App\Models;

use App\Models\Model;

class LeftoverComment extends Model
{
    public $table = 'leftover_comments';

    public function leftover()
    {
        return $this->belongsTo(Leftover::class, 'leftover_id', 'id');
    }
}
