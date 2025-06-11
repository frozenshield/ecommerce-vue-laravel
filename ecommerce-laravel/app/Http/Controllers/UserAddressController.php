<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserAddressController extends Controller
{
    public function getAllAddress()
    {
        if(!auth()->user()){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $getAllAddress = DB::select("SELECT * FROM user_address");
        return $getAllAddress ? response()->json($getAllAddress)
                              : response()->json(['message' => 'No address found'], 404);

    }   


    public function addNewAddress(Request $request)
    {
        $user = auth()->user();

        if(!$user){
            return response()->json(['message' => 'unauthorized'], 401);
        }

        $validation = Validator::make($request->all(), [
            'profile_id' => 'required|exists:profiles,profile_id',
            'address_type' => 'required|string',
            'recipient_name' => 'required|string|max:255',
            'address_line1' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'is_default' => 'boolean',
        ]);

        if($validation->fails()){
            return response()-json(['message' => 'Validation Failed', 'error' => $validation->errors()], 422);
        }

        DB::statement("INSERT INTO user_address(profile_id, address_type, recipient_name, address_line1, city, is_default, created_at, updated_at)
                                       VALUES(?, ?, ?, ?, ?, ?, ?, ?)", 
                                       [
                                          $request->input('profile_id'),
                                          $request->input('address_type'),
                                          $request->input('recipient_name'),
                                          $request->input('address_line1'),
                                          $request->input('city'),
                                          $request->input('is_default', 0),
                                          now(),
                                          now(), 
                                       ]);

        $lastInsertId = DB::getPDO()->lastInsertId();
        $newAddress = DB::selectOne("SELECT * FROM user_address WHERE user_address_id = ?", [$lastInsertId]);
        return $newAddress ? response()->json($newAddress, 201)
                           : response()->json(['message' => 'Insert address failed'], 422);
    }


    public function deleteAddress($user_address_id)
    {
        $user = auth()->user();

        if(!$user){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $deleteId = DB::delete("DELETE FROM user_address WHERE user_address_id = ?", [$user_address_id]);
        return $selectId ? response()->json($selectId) 
                         : response()->json(['message' => 'Address not found'], 404);
    
    }

   
    public function editAddress(Request $request, $user_address_id)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validation = Validator::make($request->all(), [
            'address_type' => 'required|string',
            'recipient_name' => 'required|string|max:255',
            'address_line1' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'is_default' => 'boolean',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validation->errors()
            ], 422);
        }

       
        $address = DB::selectOne("SELECT * FROM user_address WHERE user_address_id = ?", [$user_address_id]);
        if (!$address) {
            return response()->json(['message' => 'Address not found'], 404);
        }


        if ($address->profile_id != $user->profile_id) {
             return response()->json(['message' => 'Forbidden'], 403);
        }

        $updated = DB::update(
            "UPDATE user_address SET address_type = ?, recipient_name = ?, address_line1 = ?, city = ?, is_default = ?, updated_at = ? WHERE user_address_id = ?",
            [
                $request->input('address_type'),
                $request->input('recipient_name'),
                $request->input('address_line1'),
                $request->input('city'),
                $request->input('is_default', 0),
                now(),
                $user_address_id
            ]
        );

        if ($updated) {
            $newAddress = DB::selectOne("SELECT * FROM user_address WHERE user_address_id = ?", [$user_address_id]);
            return response()->json($newAddress, 200);
        } else {
            return response()->json(['message' => 'No changes made or address not found'], 422);
        }
    }
}
