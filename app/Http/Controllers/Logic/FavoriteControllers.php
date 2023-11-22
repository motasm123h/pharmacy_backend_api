<?php

namespace App\Http\Controllers\Logic;

use App\Models\User;
use App\Models\Depot;
use App\Models\Favorite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FavoriteControllers extends Controller
{
    public function makeOrDeleteFavorites($depot_id)
    {
        $depot = Depot::where('id', $depot_id)->first();

        if (!$depot) {
            return response()->json([
                'message' => 'depot not found',
            ]);
        }

        $fav = Favorite::where([
            'user_id' => auth()->user()->id,
            'depot_id' => $depot_id
        ])->first();

        if (!$fav) {
            $favorite = Favorite::create([
                'user_id' => auth()->user()->id,
                'depot_id' => $depot_id
            ]);
            return response()->json([
                'favorite' => $favorite
            ]);
        }

        return response()->json([
            'message' => $fav->delete(),
        ]);
    }

    public function getFavProduct()
    {
        $user = User::where('id', auth()->user()->id)->first();
        $products = $user->favorites()->select('depots.id', 'depots.name', 'depots.price', 'depots.quantity', 'depots.trade_name', 'depots.scientific_name', 'depots.manufacturer')->get();

        return response()->json([
            'message' => $products,
        ]);
    }
}
