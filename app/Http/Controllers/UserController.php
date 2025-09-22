<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class UserController extends Controller
{
    use ApiResponse;

    public function index() {
        $users = User::all();

        if($users->isEmpty())
            return $this->apiResponse('success', 'No users found', null);

        return $this->apiResponse('success', 'Users fetched successfully', $users);
    }

    public function register(Request $request) {
        
        $validatedFields = $request->validate([
            'phone' => 'required|min:9|max:10|unique:users,phone',
            'password' => 'required|confirmed|string|min:8'
        ]);

        $user = User::create($validatedFields);
        
        $token = $user->createToken('auth_token')->plainTextToken;
        $user["token"] = $token;
        
        return $this->apiResponse('success', 'User created successfully', $user);
    }
}
