<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cart;
use App\Models\User;


class Depot extends Model
{

    protected $fillable = [
        'scientific_name', 'trade_name', 'category',
        'manufacturer', 'quantity',
        'expir_date', 'price',
    ];
    use HasFactory;


    public function carts()
    {
        return $this->belongsToMany(Cart::class)->withPivot('quntity');
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favoraits', 'depot_id', 'user_id');
    }
}
