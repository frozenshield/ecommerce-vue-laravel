<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator as Validate;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(!auth()->user() && !auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $orders = DB::select("SELECT * FROM orders");
        return response()->json($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validate::make($request->all(), [
            'order_date' => 'required|date',
            'status' => 'required|in:pending,completed,cancelled',
            'total_amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors, 422);
        }

        $user = $request->user();

        DB::table('orders')->insert([
            'user_id' => $user->user_id,
            'order_date' => $request->input('order_date'),
            'status' => $request->input('status'),
            'total_amount' => $request->input('total_amount'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $orderID = DB::getPDO()->lastInsertId();
        $newOrder = DB::select("SELECT * FROM orders WHERE order_id = ?", [$orderID]);
        return $newOrder ? response()->json($newOrder) : response()->json(['error' => 'Failed to Create Order'], 404);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user_id = auth()->user();

        $order = DB::selectOne("SELECT * FROM orders WHERE order_id = ?", [$id]);

        if($order->user_id != $user_id->user_id){
            return response()->json(['message' => 'Invalid Order ID'], 404);
        }
        
        $orderID = DB::select("SELECT * FROM orders WHERE order_id = ?", [$id]);
        return $orderID ? response()->json($orderID) : response()->json(['message' => 'Invalid Order']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user_id = auth()->user();

        $order = DB::selectOne("SELECT * FROM orders WHERE order_id = ?", [$id])


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        $user_id = auth()->user();

        $order = DB::selectOne("SELECT * FROM orders WHERE order_id = ?", [$id]);
        if(!$order){
            return response()->json(['message' => 'Order Not Found'], 404);
        }


        if(!$user_id->isAdmin() && $order->user_id != $user_id->user_id){
            return response()->json(['message' => 'Unauthorized'], 404);
        }

        $deleted = DB::delete("DELETE FROM orders WHERE order_id = ?", [$id]);
        return $deleted ? response()->json(['message' => 'Order Deleted Successfully']) : response()->json(['message' => 'Invalid Order ID'], 404);
    }
}
