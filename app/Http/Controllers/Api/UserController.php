<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->get();

        return response([
            'message' => 'Users Details Retrieved Successfully',
            'status' => '200',
            'users' => $users
        ]);
    }

    public function update_profile(Request $request)
    {
        $users = User::find($request->user_id);
        if(empty($users)){
            return response([
                'status' => '400',
                'message' => 'User Not Found',
            ]);
        }

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = public_path('uploads/users/'); // 'uploads' is the directory inside the public path

        $file->move($filePath, $fileName);

        $users->first_name = $request->first_name;
        $users->last_name = $request->last_name;
        $users->dob = date('Y-m-d', strtotime($request->dob));
        $users->contact_no = $request->contact_no;
        $users->gender = $request->gender;
        $users->save();

        return response([
            'status' => '200',
            'message' => 'User Updated Successfully',
            'user' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
        $user = User::with('roles')->find($id);
        if(empty($user)){
            return response([
                'status' => '400',
                'message' => 'Record Not Fouund',
            ]);
        }else{
            return response([
                'status' => '200',
                'message' => 'User Retrived Successfully.',
                'user' => $user
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
        //
    }
}
