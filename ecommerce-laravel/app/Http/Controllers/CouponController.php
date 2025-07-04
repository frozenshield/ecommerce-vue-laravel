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
    public function getAllCoupon(Request $request)
    {
        $user = auth()->user();
        $userId = $user->user_id;


        if(!$user || !auth()->user()->isAdmin()){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $page = $request->input('page', 1);
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $totalCount = DB::selectOne("SELECT COUNT(*) as total FROM coupon")->total;

        $coupons = DB::select("SELECT * FROM coupon ORDER BY created_at DESC LIMIT ? OFFSET ?", [$perPage, $offset]);

        // Calculate pagination info
        $totalPages = ceil($totalCount / $perPage);
        $hasNextPage = $page < $totalPages;
        $hasPreviousPage = $page > 1;

        // Build the complete response structure
        $responseData = [
            'data' => $coupons,
            'total' => $totalCount,
            'count' => count($coupons),
            'last_page' => $totalPages,
            'pagination' => [
                'current_page' => (int)$page,
                'per_page' => $perPage,
                'total' => $totalCount,
                'total_pages' => $totalPages,
                'has_next_page' => $hasNextPage,
                'has_previous_page' => $hasPreviousPage,
                'next_page' => $hasNextPage ? $page + 1 : null,
                'previous_page' => $hasPreviousPage ? $page - 1 : null
            ]
        ];

        return response()->json($responseData, 200);
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
    public function getSpecificCoupon($coupon_id)
    {
        $user = auth()->user();
        $userId = $user->user_id;

        if(!$user && !auth()->user()->isAdmin()){
            return response()->json(['message' => 'Unauthorize'], 401);
        }

        $specificCoupon = DB::selectOne("SELECT * FROM coupon WHERE coupon_id = ?", [$coupon_id]);
        return $specificCoupon ? response()->json($specificCoupon, 200)
                               : response()->json(['message' => 'Coupon Not Found'], 404);

    }

    /**
     * Update the specified resource in storage.
     */
    public function editCoupon(Request $request, $coupon_id)
    {
        $user = auth()->user();
        $userId = $user->user_id;

        if(!$user && !auth()->user()->isAdmin()){
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $validation = Validator::make($request->all(), [
            'coupon_code' => 'required|string|max:100',
            'by_percent' => 'nullable|numeric|min:0|max:100',
            'by_currency' => 'nullable|numeric|min:0|max:9999999.99',
            'expired_date' => 'required|date|after:today',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string|max:150'
        ]);

        if($validation->fails()){
            return response()->json(['message' => 'validation failed',
                                     'error' => $validation->errors()], 422);
        }

        $rowsAffected = DB::update("UPDATE coupon SET coupon_code = ?, by_percent = ?, by_currency = ?, expired_date = ?, status = ?, description = ?, updated_at = ? WHERE coupon_id = ?", [
                
            $request->input('coupon_code'),
            $request->input('by_percent'),
            $request->input('by_currency'),
            $request->input('expired_date'),
            $request->input('status'),
            $request->input('description'),
            now(),
            $coupon_id
        ]);

        if($rowsAffected === 0){
            return response()->json(['message' => 'Coupon not Found'], 404);
        }

        $updatedCoupon = DB::selectOne("SELECT * FROM coupon WHERE coupon_id = ?", [$coupon_id]);
        return response()->json(['coupon_code' => $updatedCoupon->coupon_code,
                                 'by_percent' => $updatedCoupon->by_percent,
                                 'by_currency' => $updatedCoupon->by_currency,
                                 'expired_date' => $updatedCoupon->expired_date,
                                 'status' => $updatedCoupon->status,
                                 'description' => $updatedCoupon->description,
                                 'updated_at' => $updatedCoupon->updated_at], 200); 
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

        $deleteId = DB::delete("DELETE FROM coupon WHERE coupon_id = ?", [$coupon_id]);
        return $deleteId ? response()->json(['message' => 'Deleted Successsfully'], 200)
                         : response()->json(['message' => 'Failed to delete'], 500);
    }


    public function toggleCoupon($coupon_id){
        $user = auth()->user();
        $userId = $user->user_id;

        if(!$user && auth()->user()->isAdmin()){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $categoryStatus = DB::selectOne("SELECT status FROM coupon WHERE coupon_id =?", [$coupon_id]);

        $newStatus = ($categoryStatus->status === 'active') ? 'inactive' : 'active';

        $affectedRow = DB::update("UPDATE coupon SET status = ? WHERE coupon_id = ?", [
            $newStatus,
            $coupon_id
        ]);

        if($affectedRow === 0){
            return reponse()->json(['message' => 'No changes made or no coupon found'], 404);
        }

        $selectStatus = DB::selectOne("SELECT * FROM coupon WHERE coupon_id = ?", [$coupon_id]);
        return response()->json([$selectStatus], 200);
    }
}
