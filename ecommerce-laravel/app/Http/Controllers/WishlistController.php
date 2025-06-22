<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WishlistController extends Controller
{
    public function __construct(){
        
        $this->middleware('auth:sanctum');
        $this->middleware('admin');
    }


    public function getAllWishlist(){

        $user = auth()->user(); 

        $allWishlist = DB::select("SEELCT * FROM wishlists WHERE user_id = ?", $user->user_id);
        return $allWishlist ? response()->json([$allWishlist], 200)
                        : response()->json(['message' => 'Review nopt found'], 404);
    }


    public function addWishlist(Request $request){
        
        $user = auth()->user();

        $validation = Validator::make($request->all(), [

            'product_id' => 'required|integer|exists:products,product_id',
        ]);

        DB::insert("INSERT INTO wishlists (product_id, user_id, created_at, updated_at) VALUES (?, ?, ?, ?)", [
            $request->input('product_id'),
            $request->$user->user_id,
            now(),
            now()
        ]);

        $lastInsertedId = DB::getPdo()->lastInsertId();
        $getId = DB::selectOne("SELECT * FROM wishlists WHERE user_id =?", [$lastInsertedId]);
        return $getId ? response()->json([$getId], 200)
                      : response()->json(['message' => 'Id not found'], 404);
    }
}
