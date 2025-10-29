<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }
}
