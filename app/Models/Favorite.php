<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = ['user_id', 'depot_id'];
    use HasFactory;

    public function depots()
    {
        return $this->belongsToMany(Depot::class, 'favoraits', 'user_id', 'depot_id');
    }
}
