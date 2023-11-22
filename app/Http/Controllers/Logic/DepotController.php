<?php

namespace App\Http\Controllers\Logic;

use App\Models\Depot;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepotController extends Controller
{
    public function index()
    {
        $categories = Depot::distinct('category')->pluck('category');
        return response()->json(array('depots' => $categories));
    }

    public function getDepotByCategpry($category)
    {
        $depots = Depot::where('category', $category)->get();
        return response()->json(array('depots' => $depots));
    }

    public function addDepot(Request $request)
    {
        $atter = $request->validate([
            'scientific_name' => ['required'],
            'trade_name' => ['required'],
            'category' => ['required'],
            'manufacturer' => ['required'],
            'quantity' => ['required'],
            'expir_date' => ['required'],
            'price' => ['required'],
        ]);

        $depot = Depot::create($atter);

        return response()->json(array('depot' => $depot));
    }


    public function updateDepot(Request $request, $id)
    {
        $atter = $request->validate([
            'price'
        ]);
        $depot = Depot::where('id', $id)->first();
        $depot->update([
            'price' => $request->input('price') ?? $depot['price'],
            'scientific_name' => $request->input('scientific_name') ?? $depot['scientific_name'],
            'category' => $request->input('category') ?? $depot['category'],
            'manufacturer' => $request->input('manufacturer') ?? $depot['manufacturer'],
            'quantity' => $request->input('quantity') ?? $depot['quantity'],
            'expir_date' => $request->input('expir_date') ?? $depot['expir_date'],
        ]);

        return response()->json([
            'message' => $depot,
        ]);
    }

    public function deleteDepot($id)
    {
        $depot = Depot::where('id', $id)->first();
        return response()->json([
            'message' => $depot->delete(),
        ]);
    }
}
