<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category_id', 'user_id'];


    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // public function stocks()
    // {
    //     return $this->hasMany(Stock::class, 'item_id');
    // }

    public function stocks()
    {
        return $this->hasOne(Stock::class, 'item_id');
    }

    public function outStocks()
    {
        return $this->hasMany(OutStock::class, 'item_id');
    }

    public function damageItems()
    {
        return $this->hasMany(DamageItem::class, 'item_id');
    }
}