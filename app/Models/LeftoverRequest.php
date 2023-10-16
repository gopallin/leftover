<?php

namespace App\Models;

use App\Models\Model;

class LeftoverRequest extends Model
{
    public $table = 'leftover_requests';

    public function leftover()
    {
        return $this->belongsTo(Leftover::class, 'leftover_id', 'id');
    }
}
