<?php

namespace App\Models;

use App\Models\Model;

class LeftoverSave extends Model
{
    public $table = 'leftover_saves';

    public function leftover()
    {
        return $this->belongsTo(LeftoverSave::class, 'leftover_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
