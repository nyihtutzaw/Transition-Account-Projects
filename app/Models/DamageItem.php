<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DamageItem extends Model
{
    use HasFactory;

    protected $fillable = ['quantity', 'acceptor', 'item_id', 'user_id'];

    public function items()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}