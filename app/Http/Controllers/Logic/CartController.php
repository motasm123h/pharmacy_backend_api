<?php

namespace App\Http\Controllers\Logic;

use App\Models\Cart;
use App\Models\Depot;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function index()
    {
        cart::where([
            'user_id'=>auth()->user()->id
        ])->get();
        return response()->json([
            'data' => auth()->user()->carts()->get(),
        ]);
    }

    public function addItem(Request $request)
    {
        $validatedData = $request->validate([
            'quantity' => 'required|integer',
            'depot_id' => 'required',
        ]);
        $depot = Depot::where('id', $validatedData['depot_id'])->frist();

        if ($depot['quantity'] < $validatedData['quantity']) {
            return response()->json(['error' => 'Product quantity exceeds the available limit.'], 400);
        }
        $existingCartItem = Cart::where([
            ['depot_id', $validatedData['depot_id']],
            ['name', $depot['name']],
            ['user_id', auth()->user()->id],
        ])->first();
        if ($existingCartItem) {

            $existingCartItem->quantity += $validatedData['quantity'];
            $existingCartItem->save();


            $depot->quantity = $depot->quantity - $validatedData['quantity'];
            $depot->save();


            return response()->json([
                'message' => 'Product quantity increased in the cart.',
                'cartItem' => $existingCartItem
            ]);
        }
        $newCart = Cart::create([
            'user_id' => auth()->user()->id,
            'depot_id' => $validatedData['depot_id'],
            'name' => $depot['name'],
            'quntity' => $depot['quntity'],
            'price' => $depot['price'],
            'category' => $depot['category'],
        ]);

        $depot->quantity = $depot->quantity - $validatedData['quantity'];
        $depot->save();

        return response()->json([
            'message' => 'Product added to cart.',
            'cartItem' => $newCart
        ]);
    }

    public function deleteItemsFromCart(Request $request)
    {
        $validatedData = $request->validate([
            'cart_item_id' => 'required|integer',
        ]);

        $cartItem = Cart::where('id', $validatedData['cart_item_id'])->first();
        if (!$cartItem) {
            return response()->json(['error' => 'Cart item not found.'], 404);
        }
        if ($cartItem->user_id !== auth()->user()->id) {
            return response()->json(['error' => 'Unauthorized.'], 401);
        }

        $depot = Depot::find($cartItem->product_id);

        if ($depot) {
            $depot->quantity += $cartItem->quantity;
            $depot->save();
        }

        $cartItem->delete();
        return response()->json(['message' => 'Cart item deleted successfully.']);
    }

    public function deleteOneItemFromCart(Request $request)
    {
        $validatedData = $request->validate([
            'cart_item_id' => 'required|integer',
        ]);

        $cartItem = Cart::find($validatedData['cart_item_id']);
        if (!$cartItem) {
            return response()->json(['error' => 'Cart item not found.'], 404);
        }

        if ($cartItem->user_id !== auth()->user()->id) {
            return response()->json(['error' => 'Unauthorized.'], 401);
        }
        $depot = Depot::find($cartItem->product_id);

        if ($depot) {
            $depot->quantity += 1;
            $depot->save();
        }
        $cartItem->quantity -= 1;
        $cartItem->save();

        if ($cartItem->Quntity == 0) {
            $cartItem->delete();
            return response()->json([
                'message' => 'Cart item deleted successfully.'
            ]);
        }

        return response()->json([
            'cartItem' => $cartItem,
        ]);
    }
}
