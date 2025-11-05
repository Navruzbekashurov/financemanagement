<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'type',
        'is_active',
    ];


    public function goals()
    {
        return $this->hasMany(Goal::class);
    }

    public function debets()
    {
        return $this->hasMany(Debt::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
