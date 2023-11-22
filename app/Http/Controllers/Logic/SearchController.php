<?php

namespace App\Http\Controllers\Logic;

use App\Models\Depot;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function index($req)
    {
        $products = Depot::where('name', 'like', '%' . $req . '%')
            ->orWhere('category', 'like', '%' . $req . '%')
            ->select('*')
            ->get();

        return response()->json([
            'result' => $products
        ]);
    }
}
