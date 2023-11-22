<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthenticationController extends Controller
{
    public function rigesterAsPharma(Request $request)
    {
        $atter = $request->validate([
            'phone_number' => ['required'],
            'name' => ['required'],
        ]);

        $user = User::create($atter);
        if ($user) {
            return response()->json([
                'user' => $user,
                'token' => $user->createToken('secret')->plainTextToken,
            ]);
        }
        return response()->json([
            'message' => 'sorry you cant register'
        ]);
    }

    public function rigesterAsDepot(Request $request)
    {
        $atter = $request->validate([
            'phone_number' => ['required', 'string', 'unique:' . User::class],
            'name' => ['required'],
            'password' => ['required'],
            'email' => ['required', 'string', 'email', 'unique:' . User::class],
            'address' => ['required'],
            'property_name' => ['required'],
            'type' => ['required'],
        ]);

        $atter['password'] = Hash::make($atter['password']);

        if ($atter['type'] == 'pharma') {
            $atter['role'] = 0;
        } else {
            $atter['role'] = 1;
        }

        $user = User::create($atter);
        if ($user) {
            return response()->json([
                'user' => $user,
                'token' => $user->createToken('secret')->plainTextToken,
            ]);
        }
        return response()->json([
            'message' => 'sorry you cant register'
        ]);
    }


    public function logInPharma(Request $request)
    {
        $user = User::where('name', $request->input('name'))
            ->where('phone_number', $request->input('phone_number'))
            ->first();
            
        if ($user) {
            Auth::login($user);
            return response([
                'token' => $user->createToken('secret')->plainTextToken,
                'user' => $user,
            ]);
        } else {
            return response([
                'message' => 'Invalid Credentials'
            ], 403);
        }
    }

    public function logInDepot(Request $request)
    {
        $atter = $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($atter)) {
            return response([
                'message' => 'Inavild Crdenatail'
            ], 403);
        }

        return response([
            'user' => auth()->user(),
            'token' => auth()->user()->createToken('secret')->plainTextToken
        ], 200);
    }

    public function logout()
    {
        return response()->json([
            'mess' => "ture",
            'message' => auth()->user()->tokens()->delete(),
        ]);
    }

    public function updateInfo(Request $request){
        $user = auth()->user();
        $userAfterEdit = $user->update([
            'name' => $request->input('name') ?? $user['name'],
            // 'email' => $request->input('email') ?? $user['email'],
            'address' => $request->input('address') ?? $user['address'],
            'property_name' => $request->input('property_name') ?? $user['property_name'],
            // 'phone_number' => $request->input('phone_number') ?? $user['phone_number'],
        ]);

        return response()->json([
            'message' => $userAfterEdit,
        ]);
    }
}
