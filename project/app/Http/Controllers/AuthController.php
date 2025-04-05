<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function register(AuthRequest $request){
        try {
        $data = $request->validated();
            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_anme'],
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            return response()->json(["User"=>$user], 201);

        } catch (Exception $e) {
            return response()->json(["message"=>"error", "error"=>$e->getMessage()], 500);
        }
    }

    public function login(AuthRequest $request){
        try{
            $data = $request->validated();

            $user = User::where("email", $data['email'])->first();

            if (!$user || !Hash::check($data['password'], $user->password)){
                return response()->json(["message"=>"Email or Password is incorrect !"], 401);
            }

            $token = $user->createToken('user_token_' . $user->id)->plainTextToken;

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
