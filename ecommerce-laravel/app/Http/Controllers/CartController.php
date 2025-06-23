<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redis;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function showAllCart()
    {
        $user = auth()->user();
        $userId = $user->user_id;

        $cachedCart = Redis::get("cart:$userId");

        if($cachedCart){
            return response()->json(json_decode($cachedCart, true));
        }

        $cart_user = DB::selectOne("SELECT * FROM cart_items WHERE user_id = ?", [$userId]);
        
        if(!$cart_user){
            return response()->json(['message' => 'Empty Cart'], 404);
        }

        if(!auth()->user()->isAdmin() && $cart_user->user_id != $user->user_id){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $cart = DB::select("SELECT * FROM cart_items WHERE user_id = ?", [$userId]);
            
        Redis::setex("cart:$userId", 6600, json_encode($cart));

        return response()->json($cart);
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function addToCart(Request $request)
    {

       $user_id = auth()->user(); 

       $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,product_id',
            'quantity' => 'required|integer|min:1',
       ]);

       if($validator->fails()){
            return response()->json($validator->errors, 422);
       }


       $existing = DB::table('cart_items')
        ->where('user_id', $user_id->user_id)
        ->where('product_id', $request->input('product_id'))
        ->first();

       if ($existing) {
        //Update Quantity
       DB::table('cart_items')
        ->where('cart_id', $existing->cart_id)
        ->update([
            'quantity' => $existing->quantity + $request->input('quantity'),
            'updated_at' => now()
        ]);
        $cartItem = DB::selectOne("SELECT * FROM cart_items WHERE cart_id = ?", [$existing->cart_id]);

       } else {

            $cartID = DB::table('cart_items')->insertGetId([
                    'user_id' =>$user_id->user_id,
                    'product_id' => $request->input('product_id'),
                    'quantity' => $request->input('quantity'),
                    'created_at' => now(),
                    'updated_at' => now()
            ]);

            $cartAdded = DB::selectOne("SELECT * FROM cart_items WHERE cart_id = ?", [$cartID]);
            return $cartAdded ? response()->json(['Message' => 'Successfully Added to Cart'], 202) : response()->json(['message' => 'Failed to Add to Cart'], 404);
        } 
       
        Redis::del("cart:" . $user_id->user_id);
        Redis::del("cart_item:" . $cartItem->cart_id);
    }

    /**
     * Display the specified resource.
     */
    public function showSpecificCart($cart_id)
    {
        $admin = auth()->user()->isAdmin();
        $user = auth()->user();

        $userId = $user->user_id;
        
        $cachedId = Redis::get("cart_item:$cart_id");
        if($cachedId){
            return response()->json(json_decode($cachedId, true));
        }
     
        if(!$admin &&  !$user->user_id){
            return response()->json(['message' , 'Unauthorized'], 401);
        }

        $cart_items = DB::select("SELECT * FROM cart_items WHERE cart_id = ?", [$cart_id]);
        
        Redis::setex("cart_item:$cart_id", 6600, json_encode($cart_items));

        return response()->json($cart_items);
    }

    /**
     * Update the specified resource in storage.
     */
    public function editQuantity(Request $request, $cart_id)
    {
        $user = auth()->user();

        $cart_user = DB::selectOne("SELECT * FROM cart_items WHERE cart_id = ?", [$cart_id]);
        if(!$cart_user){
            return response()->json(['message' => 'Empty Cart'], 404);
        }

        if(!auth()->user()->isAdmin() && $user->user_id != $cart_user->user_id){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validation = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
            'product_id' => 'required|integer|exists:products,product_id',
        ]);

        if ($validation->fails()){
            return response()->json($validation->errors(), 422);
        }
        
        $update_quantity = DB::update("UPDATE cart_items SET
            quantity = ?, updated_at =  ? WHERE cart_id = ?",
            [$request->input('quantity'), now(), $cart_id]);

        return $update_quantity ? response()->json([$update_quantity], 200) : response()->json(['message' => 'Failed to Update'], 404);

        Redis::del("cart" . $user->user_id);
        Redis::del("cart_item" . $cart_id->cart_id);
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function removeToCart($cart_id)
    {
        $user_id = auth()->user();

        $cart_user = DB::selectOne("SELECT * FROM cart_items WHERE cart_id = ?", [$cart_id]);
        if(!$cart_user){
            return response()->json(['message' => 'Empty Cart'], 404);
        }

        if(!auth()->user()->isAdmin() && $cart_user->user_id != $user_id->user_id){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $item_remove = DB::delete("DELETE FROM cart_items WHERE cart_id = ?", [$cart_id]);
        return $item_remove ? response()->json(['message' => 'Item Removed'], 200) : response()->json(['message' => 'Not Found'], 404);
    
        Redis::del("cart" . $user_id->user_id);
        Redis::del("cart" . $cart_id->cart_id);
    }
}
