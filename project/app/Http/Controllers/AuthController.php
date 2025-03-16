<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function register(Request $request){
        try {
            $validate = Validator::make($request->all(), [
                'name' => 'required|string|max:225',
                'email' => 'required|email|string|max:225|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            if($validate->fails()){
                return response()->json(["message"=>"error", "error"=>$validate->errors()], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json(["Users"=>$user], 201);

        } catch (Exception $e) {
            return response()->json(["message"=>"error", "error"=>$e->getMessage()], 500);
        }
    }

    public function login(Request $request){
        try{
            $validate = Validator::make($request->all(), [
                'email' => 'required|string|email|max:225',
                'password' => 'required|string|min:8',
            ]);

            if ($validate->fails()){
                return response()->json(["message"=>"error", "error"=>$validate->errors()], 401);
            }

            $user = User::where("email", $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)){
                return response()->json(["message"=>"Email or Password is incorrect !"], 401);
            }

            $token = $user->createToken("Abdelilah",["*"])->plainTextToken;

            return response()->json(["message"=>"you have login by succesfully", "token"=>$token], 200);

        } catch (Exception $e){
            return response()->json(["message"=>"error", "error"=>$e->getMessage()], 500);
        }
    }

    public function logout(Request $request){
        try{
            $request->user()->currentAccessToken()->delete(); //delete all tokens

            return response()->json(["message"=>"you logout"], 200);

        } catch (Exception $e){
            return response()->json(["message"=>"error", "error"=>$e->getMessage()], 500);
        }
    }
}
