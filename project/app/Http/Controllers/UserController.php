<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        if($users->isEmpty()) {
            return response()->json(['message' => 'No users found'], 404);
        }

        return response()->json($users, 200);
    }

    public function show(string $id)
    {
        $user = User::findOrFail($id);


        return response()->json($user, 200);
    }

    public function update(Request $request, string $id)
    {
            try {
                $user = User::findOrFail($id);
                $user->update($request->all());
                return response()->json($user, 200);
            } catch (Exception $e) {
                return response()->json(['message' => 'User not updated', 'error' => $e->getMessage()], 400);
            }
    }

    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(null, 204);
        } catch (Exception $e) {
            return response()->json(['message' => 'User not deleted', 'error' => $e->getMessage()], 500);
        }
    }

}
