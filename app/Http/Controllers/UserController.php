<?php

namespace App\Http\Controllers;

use App\Models\Refund;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['store']]);
    }
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->job_role= $request->job_role;
        $user->password = bcrypt($request->password);
        if($user->save()) {
            return response()->json(['success' => true, 'message' => 'User created successfully', 'data'=>$user], 200);
        }
        
        return response()->json(['success' => false, 'message' => 'User could not be created'], 500);
    }

    public function show($id)
    {
        if(!$user = User::find($id)){
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }
        
        $refunds = Refund::where('User', $id)->get();
        
        $user->refunds = $refunds;

        if($user) {
            return response()->json($user);
        }
        return response()->json(['success' => false, 'message' => 'User not found'], 404);
    }

    public function update(Request $request, $id)
    {
       if($user = User::find($id)){
        
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',    
                'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
                'job_role' => 'required|string|max:255',
            ]);

            if($validator->fails()){
                return response()->json($validator->errors(), 400);
            }


            $user->name = $request->name;
            $user->email = $request->email;
            $user->job_role= $request->job_role;

            if($request->filled('password')){
                $user->password = bcrypt($request->password);
            }
            if($user->save()) {
                return response()->json(['success' => true, 'message' => 'User updated successfully'], 200);
            }   

            return response()->json(['success' => false, 'message' => 'User could not be updated'], 500);
        }
        return response()->json(['success' => false, 'message' => 'User not found'], 404);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if($user) {
            if($user->delete()) {
                return response()->json(['success' => true, 'message' => 'User deleted successfully'], 200);
            }
            return response()->json(['success' => false, 'message' => 'User could not be deleted'], 500);
        }
        return response()->json(['success' => false, 'message' => 'User not found'], 404);
    }
}
