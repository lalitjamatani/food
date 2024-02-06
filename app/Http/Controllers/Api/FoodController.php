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
        $foods = Food::with('user')->where('type', 'request')->where('accept_id', null)->where('expired', '!=', '1')->get();

        return response([
            'status' => 200,
            'message' => 'Data Retrieved Successfully',
            'data' => $foods
        ]);
    }

    public function get_food_donate_list(){
        $foods = Food::with('user')->where('type', 'donate')->where('accept_id', null)->where('expired', '!=', 1)->get();

        return response([
            'status' => 200,
            'message' => 'Data Retrieved Successfully',
            'data' => $foods
        ]);
    }

    public function create_donate_food(Request $request){
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
            'message' => 'Food Donnate Added Sucessfully',
            'data' => $food,
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
        $food = Food::with('user')->find($id);
        if(empty($food)){
            return response([
                'status' => '400',
                'message' => 'Record Not Fouund',
            ]);
        }else{
            return response([
                'status' => '200',
                'message' => 'Food Record Retrived Successfully.',
                'food' => $food
            ]);
        }
    }

    public function update_donate_food(Request $request){
        $input_data = $request->all();
        $food = Food::find($id);
        if(empty($food)){
            return response([
                'status' => 400,
                'message' => 'Donation Not Found',
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
            'message' => 'Food Donnate Added Sucessfully',
            'data' => $food,
        ]);
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
            if($food->type == 'donate'){
                $message = 'Food Donation Deleted Successfully';
            }else{
                $message = 'Food Request Deleted Successfully';
            }
            return response([
                'status' => 200,
                'message' => $message,
                'data' => $food,
            ]);
        }else{
            return response([
                'status' => 400,
                'message' => 'Record Not Found',
            ]);
        }
    }

    public function accept_food_request(Request $request){
        $food = Food::find($request->food_id);
        if(empty($food)){
            return response([
                'status' => '400',
                'message' => 'Request Not Found'
            ]);
        }

        $food->accept_id = $request->user_id;
        $food->save();

        return response([
            'status' => '200',
            'message' => 'Request Accepted Suucessfully',
        ]);
    }
}
