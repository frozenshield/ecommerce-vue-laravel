<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProfilesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getMyProfile()
    {
        $user = auth()->user();

        if(!$user){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $getProfile = DB::selectOne("SELECT * FROM profiles WHERE user_id = ?", [$user->user_id]);
        return $getProfile ? response()->json($getProfile, 200)
                           : response()->json(['message' => 'User Profile has not been set'], 404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function addProfile(Request $request)
    {
        $user = auth()->user();

        if(!$user){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $exist = DB::selectOne("SELECT user_id FROM profiles WHERE user_id = ?", [$user->user_id]);
        if($exist){
            return response()->json(['message' => 'Click edit to modify the profile'], 409);
        }

        $validation = Validator::make($request->all(), [

            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'phone_number' => 'required|string|max:25',
            'address' => 'required|string|max:255',
            'barangay' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'municipality' => 'required|string|max:50',
            'gender' => 'required',
            'birth_date' => 'required|date',
        ]);

        if($validation->fails()){
            return response()->json(['message' => 'Validation Failed', 'errors' => $validation->errors()]);
        }

        DB::insert("INSERT INTO profiles(user_id, first_name, last_name, phone_number, address, Barangay, city, municipality, gender, birth_date, created_at, updated_at)
                                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                                    [
                                       $user->user_id,
                                       $request->input('first_name'),
                                       $request->input('last_name'),
                                       $request->input('phone_number'),
                                       $request->input('address'),
                                       $request->input('barangay'),
                                       $request->input('city'),
                                       $request->input('municipality'),
                                       $request->input('gender'),
                                       $request->input('birth_date'),
                                       now(),
                                       now(),
                                    ]);

        $lastId = DB::getPdo()->lastInsertId();
        $newProfile = DB::selectOne("SELECT * FROM profiles WHERE profile_id = ?", [$lastId]);
        return $newProfile ? response()->json($newProfile, 200)
                           : response()->json(['message' => 'Failed to add'], 500);

    }

  
    /**
     * Update the specified resource in storage.
     */
    public function editProfile(Request $request, $profile_id)
    {
        $user = auth()->user();

        if(!$user){
            return response()->json(['message', 'Unauthorized'], 401);
        }

        $validation = Validator::make($request->all(), [
            'first_name' => 'sometimes|string|max:50',
            'last_name' => 'sometimes|string|max:50',
            'phone_number' => 'sometimes|string|max:25',
            'address' => 'sometimes|string|max:255',
            'barangay' => 'sometimes|string|max:50',
            'city' => 'sometimes|string|max:50',
            'municipality' => 'sometimes|string|max:50',
            'gender' => 'sometimes',
            'birth_date' => 'sometimes'
        ]);

        if($validation->fails()){
            return response()->json(['message' => 'validation failed', $validation->errors()], 422);
        }

        $affectedRow = DB::update("UPDATE profiles SET first_name = ?, last_name = ?, phone_number = ?, address = ?, barangay = ?, city = ?, municipality = ?, gender = ?, birth_date = ?, updated_at = ? WHERE profile_id = ?", 
        [
            $request->input('first_name'),
            $request->input('last_name'),
            $request->input('phone_number'),
            $request->input('address'),
            $request->input('barangay'),
            $request->input('city'),
            $request->input('municipality'),
            $request->input('gender'),
            $request->input('birth_date'),
            now(),
            $profile_id
        ]);

        if($affectedRow === 0){
            return response()->json(['message' => 'no changes made or profile not found'], 404);
        }

        $updatedProfile = DB::selectOne("SELECT * FROM profiles WHERE profile_id = ?", [$profile_id]);
        return response()->json(['message' => 'Updated Successfully', 'profile' => $updatedProfile], 200);
    } 
}
