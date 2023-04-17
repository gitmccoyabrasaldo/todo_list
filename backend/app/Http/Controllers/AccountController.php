<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class AccountController extends Controller
{
    public function index(){
       $accounts = Account::all();

       if($accounts->count()>0){
        return response()->json([
            'status' => 200,
            'accounts' => $accounts
        ], 200);
       }else{

        return response()->json([
            'status' => 404,
            'message' => 'No Accounts Found'
        ], 404);
       }

    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'email' => 'required|email|unique:accounts|max:191',
            'password' => 'required|string|min:6',
            'confirm_password' => 'required|string|min:6|same:password'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }else{
            $account = Account::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                // 'confirm_password' => $request->confirm_password
            ]);
        }

        if($account){
            return response()->json([
                'status' => 200,
                'message' => 'Account Created Successfully'
            ], 200);

        }else{
            return response()->json([
                'status' => 500,
                'message' => "Something Went Wrong!"
            ], 500);
        }
    }
}
