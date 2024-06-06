<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StaffAuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:staff',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,manager',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $staff = Staff::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return response()->json([
            'status'=>'registered successfully',
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $request->only('email', 'password');

        if (!Auth::guard('staff')->attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $staff = Auth::guard('staff')->user();
        $token = $staff->createToken('StaffToken')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'status' => 'login successfull',
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();//deleting the current token

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
