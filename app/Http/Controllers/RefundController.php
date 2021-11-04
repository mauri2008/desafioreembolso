<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Refund
;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RefundController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        $refunds = Refund::join('users', 'users.id', '=', 'refunds.User')
            ->select('refunds.*', 'users.name', 'users.email', 'users.job_role')
            ->orderBy('refunds.id', 'desc')
            ->simplePaginate(10);

        return response()->json($refunds);

    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'type' => 'required|string|max:255',
            'description' => 'required|string',
            'value' => 'required|string',
            'date'=> 'required|date',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }


        $refund = new Refund;
        $refund->User = Auth::user()->id;
        $refund->type = $request->type;
        $refund->description = $request->description;
        $refund->value = $request->value;
        $refund->date = $request->date;
        $refund->save();

        return response()->json($refund);
    }

    public function show($id)
    {
       if(!$refunds = Refund::join('users', 'users.id', '=', 'refunds.User')
            ->select('refunds.*', 'users.name')
            ->orderBy('refunds.id', 'desc')
            ->where('refunds.id', $id)
            ->get())
        {
            return response()->json(['message' => 'Refund not found'], 404);
        }

        return response()->json($refunds);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        if( !$refund = Refund::find($id))
        {
            return response()->json(['message' => 'Refund not found'], 404);
        }

        $refund->value = $request->value;

        if($refund->save()) {
            return response()->json(['success' => true, 'message' => 'Refund update successfully', 'data'=>$refund], 200);
        }
        
        return response()->json(['success' => false, 'message' => 'Refund could not be updated'], 500);

    }

    public function destroy($id)
    {
        $refund = Refund::find($id);
        if($refund) {
            if($refund->delete()) {
                return response()->json(['success' => true, 'message' => 'Refund deleted successfully'], 200);
            }
            return response()->json(['success' => false, 'message' => 'Refund could not be deleted'], 500);
        }
        return response()->json(['success' => false, 'message' => 'Refund not found'], 404);
    
    }
}
