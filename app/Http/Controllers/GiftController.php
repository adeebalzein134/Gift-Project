<?php

namespace App\Http\Controllers;

use App\Models\Gift;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;


class GiftController extends Controller implements HasMiddleware
{
    use ApiResponse;

    public static function middleware(): array {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show']),
        ];
    }

    public function index() {

        $gifts = Gift::all();
        if($gifts -> isEmpty())
            return $this -> apiResponse('success', 'No gifts found', null);

        return $this -> apiResponse('success', 'Gifts fetched successfully', $gifts);
    }

    public function store(Request $request) {
        $fields = $request->validate([
            'name' => 'required|min:5|max:15',
            'price' => 'required|integer|min:500|max:999999',
            'description' => 'nullable|string'
        ]);

        $gift = $request->user()->gifts()->create($fields);

        return $this->apiResponse('success', 'Gift created successfully', $gift); 
    }

    public function show($id) {
        
        $gift = Gift::find($id);

        if(!$gift)
            return $this->apiResponse('error', 'No gift found', null);

        return $this->apiResponse('success', 'Gift fetched successfully', $gift);
    }

    public function update(Request $request, $id) {

        $gift = Gift::find($id);

        if(!$gift)
            return $this->apiResponse('error', 'No gift found', null, 400);

        $fields = $request->validate([
            'name' => 'required|min:5|max:15',
            'price' => 'required|integer|min:500|max:999999',
            'description' => 'nullable|string'
        ]);

        $gift->update($fields);

        return $this->apiResponse('success', 'Gift updated successfully', $gift);
    }

    public function destroy($id) {

        $gift = Gift::find($id);
        if(!$gift)
            return $this->apiResponse('error', 'Gift not found', null, 400);

        $gift->delete();
        return $this->apiResponse('success', 'Gift deleted successfully', null, 200);
    }
}
