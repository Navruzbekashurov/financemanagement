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
        'category_id', // default category
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Har bir goal uchun transactionlar
    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'entity');
    }

    // Goalga tegishli category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}


