<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'target_amount',
        'current_amount',
        'deadline',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }
}
