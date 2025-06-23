<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class WishlistController extends Controller
{
    
    public function getAllWishlist(){

        $user = auth()->user(); 

        $userId = $user->user_id;

        $cachedUser = Redis::get("wishlist:$userId");
        if($cachedUser){
            return response()->json(json_decode($cachedUser, true));
        }

        if(!auth()->user()->isAdmin() && !$userId){
            return response()->json(['message' => 'Unauthorize'], 401);
        }

        $allWishlist = DB::select("SELECT * FROM wishlist WHERE user_id = ?", [$userId]);
        
        Redis::setex("wishlist:$userId", 6600, json_encode($allWishlist));

        return response()->json($allWishlist, 200);
    }


    public function addWishlist(Request $request){
        
        $user = auth()->user();

        $validation = Validator::make($request->all(), [

            'product_id' => 'required|integer|exists:products,product_id',
        ]);

        $exists = DB::select("SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?", [$user->user_id, $request->input('product_id')]);
        if($exists){
            return response()->json(['message' => 'Item already exists'], 409);
        }

        DB::insert("INSERT INTO wishlist (product_id, user_id, created_at, updated_at) VALUES (?, ?, ?, ?)", [
            $request->input('product_id'),
            $user->user_id,
            now(),
            now()
        ]);

        $lastInsertedId = DB::getPdo()->lastInsertId();
        $getId = DB::selectOne("SELECT * FROM wishlist WHERE user_id =?", [$lastInsertedId]);
        return $getId ? response()->json($getId, 200)
                      : response()->json(['message' => 'Id not found'], 404);

        Redis::del("wishlist:" . $user->user_id);
    }

    public function removeWishlist($wishlistId){
        $user = auth()->user();

        $userId = $user->user_id;

        $deletedRow = DB::delete("DELETE FROM wishlist WHERE user_id = ? AND wishlist_id = ?", [
                $userId,
                $wishlistId
        ]);

        return $deletedRow ? response()->json(['message' => 'Item Removed Successfully'], 200)
                           : response()->json(['message' => 'Wishlist Item not found']);
        
        Redis::del("wishlist:" . $wishlistId);
        
    }
}
