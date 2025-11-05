<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class
Debt extends Model
{
    protected $fillable = [
        'user_id',
        'creditor',
        'amount',
        'due_date',
        'is_active',
        'category_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Debetga tegishli transactionlar
    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'entity');
    }

    // Debetga tegishli category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
