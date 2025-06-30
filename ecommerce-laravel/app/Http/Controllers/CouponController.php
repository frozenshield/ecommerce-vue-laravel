<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getAllCoupon()
    {
        $user = auth()->user();
        $userId = $user->user_id;

        if(!$user && !auth()->user()->isAdmin()){
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
        $coupons = DB::select("SELECT * FROM coupon");

        return $coupons ? response()->json([$coupons], 200)
                        : response()->json(['message' => 'Not Found'], 404);
         
    }

    /**
     * Store a newly created resource in storage.
     */
    public function createCoupon(Request $request)
    {
        $user = auth()->user();
        $userId = $user->user_id;

        if(!auth()->user()->isAdmin()){
            return response()->json(['messagfe' => 'Unauthorized'], 401);
        }

        $validation = Validator::make($request->all(), [

            'coupon_code' => 'required|string|max:100|unique:coupon,coupon_code',
            'by_percent' => 'nullable|numeric|min:0|max:100',
            'by_currency' => 'nullable|numeric|min:0|max:9999999.99',
            'expired_date' => 'required|date|after:today',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string|max:150'

        ]);

        if($validation->fails()){
            return response()->json(['message' => 'Validation Failed',
                                     'errors' => $validation->errors()], 422);
        }

        DB::insert("INSERT INTO coupon(coupon_code, by_percent, by_currency, expired_date, status, description, created_at) VALUES(?, ?, ?, ?, ?, ?, ?)", [
            $request->input('coupon_code'),
            $request->input('by_percent'),
            $request->input('by_currency'),
            $request->input('expired_date'),
            $request->input('status'),
            $request->input('description'),
            now()
        ]);


        $lastInsertId = DB::getPdo()->lastInsertId();
        $getId = DB::selectOne("SELECT * FROM coupon WHERE coupon_id = ?", [$lastInsertId]);
        return $getId ? response()->json($getId, 200)
                      : response()->json(['message' => 'ID not found'], 404);
    }

    /**
     * Display the specified resource.
     */
    public function show($coupon_id)
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
    public function deleteCoupon($coupon_id)
    {
        $user = auth()->user();
        $userId = $user->user_id;

        if(!$user && auth()->user()->isAdmin()){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $deleteId = DB::delete("DELETE FROM coupon WHERE coupon_id = ?", $coupon_id);
        return $deleteId ? response()->json(['message' => 'Deleted Successsfully'], 200)
                         : response()->json(['message' => 'Failed to delete'], 500);
    }
}
