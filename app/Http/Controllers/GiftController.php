<?php

namespace App\Http\Controllers;

use App\Models\Gift;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;




class GiftController extends Controller
{
    use ApiResponse;

    public function index() {

        $gifts = Gift::all();
        if($gifts -> isEmpty())
            return $this -> apiResponse('success', 'No gifts found', null);

        return $this -> apiResponse('success', 'Gifts fetched successfully', $gifts);
    }

    public function store(Request $request) {
        $fields = $request->validate([
            'user_id' => 'required',
            'name' => 'required | min:5 | max:15',
            'price' => 'required | integer | min: 500 | max: 999999',
            'description' => ''
        ]);

        $gift = Gift::create($fields);

        return $this->apiResponse('success', 'Gift created successfully', $gift); 
    }

    public function show($id) {
        
        $gift = Gift::find($id);
        
        if(!$gift)
            return $this->apiResponse('error', 'No gift found', null);

        return $this->apiResponse('success', 'Gift fetched successfully', $gift);
    }
}
