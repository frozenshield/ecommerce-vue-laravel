<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->isAdmin()){
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $users = DB::select("SELECT user_id, username, email, role FROM users");
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string',
            'role' => 'sometimes|in:customer,seller'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $hashedPassword = Hash::make($request->password);

            DB::table('users')->insert([
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => $hashedPassword,
            'role' => $request->input('customer','seller'), // Default role is 'user'
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $userId = DB::getPdo()->lastInsertId();
        $newUser = DB::selectOne("SELECT username, email, role FROM users WHERE user_id = ?", [$userId]);
        return response()->json([
            'user' => $newUser,
            'message' => 'User created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = DB::selectOne("SELECT username, email, role FROM users WHERE user_id = ?", [$id]);
        return $user ? response()->json($user) : response()->json(['error' => 'Not Found'], 404); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (auth()->id() != $id && !auth()->User()->isAdmin()){
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user = DB::selectOne("SELECT * FROM users WHERE user_id = ?", [$id]);
        if ($user === 0) {
            return response()->json(['message' => 'No Valid User'], 404);
        }

        $validator = Validator::make($request->all(), [
            'username' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|max:255',
            'role' => 'sometimes|in:user,admin',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $updateFields = [];
        $params = [];

        if ($request->has('username')){
            $updateFields[] = 'username = ?';
            $params[] = $request->name;
        }

        if ($request->has('email')){
            $updateFields[] = 'email = ?';
            $params[] = $request->email;
        }

        if ($request->has('password')){
            $updateFields[] = 'password = ?';
            $params[] = Hash::make($request->password);
        }

        if($request->has('role')){
            if (!auth()->user()->isAdmin()){
                return response()->json(['message' => 'Only Admin Can Change Roles'], 404);
            }
            $updateFieds[] = 'role = ?';
            $params[] = $request->role;
        }

        if (!empty($updateFields)) {
            $updateFields[] = 'updated_at = NOW()';
            $query = "UPDATE users SET " . implode(', ', $updateFields) . " WHERE user_id = ?";
            $params[] = $id;
            
            DB::update($query, $params);
        }

        $updatedUser = DB::selectOne("SELECT username, email, role, created_at, updated_at
                     FROM users WHERE id = ?", [$id]);
            return response()->json(["message" => "User updated successfully", 'user' => $updateUser]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (auth()->id() != $id && !auth()->user()->isAdmin()){
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $delete_user = DB::selectOne("DELETE FROM users WHERE user_id = ?", [$id]);

        if($deleted_user === 0) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json(['message' => 'User Deleted Successfully']);
        

    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string|max:100',
            'password' => 'required',
        ]);

        $user = DB::selectOne("SELECT * FROM users WHERE username = ?", [$credentials['username']]);

        if (!$user || !Hash::check($credentials['password'], $user->password)){
            return response()->json(['message' => 'Invalid User Account'], 403);
        }

        $userModel = \App\Models\User::where('username', $credentials['username'])->first();
        $token = $userModel->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $userModel,
            'role' => $userModel->role,
            'token' => $token,
            'message' => 'Login Success'
        ]);

    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'logout successfully']);
    }
}
