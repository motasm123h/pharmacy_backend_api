<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Depot;
use App\Models\User;

class Cart extends Model
{
    protected $fillable = [
        'user_id', 'depot_id', 'name',
        'quntity', 'category', 'price',
    ];
    use HasFactory;


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Depot::class)->withPivot('quntity');
    }
}
