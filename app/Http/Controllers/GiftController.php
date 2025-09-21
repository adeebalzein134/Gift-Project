<?php

namespace App\Http\Controllers;

use App\Models\Gift;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;




class GiftController extends Controller
{
    use ApiResponse;

    public function store(Request $request) {
        $fields = $request->validate([
            'user_id' => 'required',
            'name' => 'required | min:5 | max:15',
            'price' => 'required | integer | min: 500 | max: 999999'
        ]);

        $gift = Gift::create($fields);

        return $this->apiResponse('success', 'Gift created successfully', $gift); 
    }
}
