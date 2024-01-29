<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function get_food_request_list(){
        $foods = Food::with('user')->where('type', 'request')->where('expired', '!=', '1')->get();

        return response([
            'status' => 200,
            'message' => 'Data Retrieved Successfully',
            'data' => $foods
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $input_data = $request->all();
        $food = new Food;
        $food->user_id = $request->user_id;
        $food->title = $request->title;
        $food->type = $request->type;
        $food->text = $request->text;
        $food->quantity = $request->quantity;
        $food->location = $request->location;
        $food->expired = 0;
        $food->save();

        return response([
            'status' => 200,
            'message' => 'Food Request Added Sucessfully',
            'data' => $food,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        $input_data = $request->all();
        $food = Food::find($id);
        if(empty($food)){
            return response([
                'status' => 400,
                'message' => 'Request Not Found',
            ]);
        }
        $food->user_id = $request->user_id;
        $food->title = $request->title;
        $food->type = $request->type;
        $food->text = $request->text;
        $food->quantity = $request->quantity;
        $food->location = $request->location;
        $food->expired = 0;
        $food->save();

        return response([
            'status' => 200,
            'message' => 'Food Request Updated Sucessfully',
            'data' => $food,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $food = Food::find($id);
        if(!empty($food)){
            $food->delete();
            return response([
                'status' => 200,
                'message' => 'Food Request Deleted Sucessfully',
                'data' => $food,
            ]);
        }else{
            return response([
                'status' => 400,
                'message' => 'Request Not Found',
            ]);
        }
    }
}
