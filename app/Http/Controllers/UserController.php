<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    use ApiResponse;

    public function index() {
        $users = User::all();

        if($users->isEmpty())
            return $this->apiResponse('success', 'No users found', null);

        return $this->apiResponse('success', 'Users fetched successfully', $users);
    }

    public function show($id) {
        $user = User::find($id);
        if(!$user) {
            return $this->apiResponse('error', 'No users found', null);
        }

        return $this->apiResponse('success', 'User found', $user);
    }

    public function update(Request $request, $id) {
        $user = User::find($id);
        if(!$user)
            return $this->apiResponse('error', 'User not found', null);

        $validatedFields = $request->validate([
            'phone' => 'required|min:9|max:10|unique:users,phone',
            'password' => 'required|confirmed|string|min:8'
        ]);

        $user->update($validatedFields);

        return $this->apiResponse('success', 'User updated successfully', $user);
    }

    public function destroy($id) {
        $user = User::find($id);

        if(!$user)
            return $this->apiResponse('error', 'User not found', null);
        
        $user->delete();
        return $this->apiResponse('success', 'User deleted successfully', null);
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

    public function login(Request $request) {

        $user = User::where('phone', $request->phone)->first();

        if(!$user || !Hash::check($request->password, $user->password)) 
            return $this->apiResponse('error', 'Error in login', null);

        $token = $user->createToken('auth_token')->plainTextToken;
        $user["token"] = $token;
        return $this->apiResponse('success', 'User loged in successfully', $user);
    }  
    
    public function logout(Request $request) {
        $request->user()->tokens()->delete();
        
        return $this->apiResponse('success', 'User loged out successfullly', null);
    }
}
