<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = DB::select('SELECT * FROM products');
        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'product_description' => 'required|string|max:1000',
            'product_price' => 'required|numeric|min:0',
            'product_stock' => 'required|integer|min:0',
            'image_url' => 'nullable|url|max:255'
        ]);

        $id = DB::table('products')->insertGetId([
            'product_name' => $validated['product_name'],
            'product_description' => $validated['product_description'],
            'product_price' => $validated['product_price'],
            'product_stock' => $validated['product_stock'],
            'image_url' => $validated['image_url'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        $product = DB::select("SELECT * FROM products WHERE product_id = ?", [$id]) ;
        return response()->json($product, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $products = DB::select("SELECT * FROM products WHERE product_id = ?", [$id]);
        return $products ? response()->json($products) : response()->json(['error' => 'Not found'], 404); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'product_description' => 'required|string|max:1000',
            'product_price' => 'required|numeric|min:0',
            'product_stock' => 'required|integer|min:0',
            'image_url' => 'nullable|url|max:255'
        ]);

        $updated = DB::update("
            UPDATE products
            SET product_name = ?,
                product_description = ?,
                product_price = ?,
                product_stock = ?,
                image_url = ?,
                updated_at = NOW()
            WHERE product_id = ?",
            [
                $validated['product_name'],
                $validated['product_description'],
                $validated['product_price'],
                $validated['product_stock'],
                $validated['image_url'],
                $id
            ]);

            if($updated === 0) {
                return response()->json(['error' => 'Product Not Found'], 404);
            }

            $product = DB::select("SELECT * FROM products WHERE product_id = ?", [$id]);
            return response()->json($product);
        }
            
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if(auth()->id()->isSeller() != $id && auth()->user()->isAdmin()){
            return response()->json(['message' => 'Unauthenticated'], 404);
        }

        $deleted = DB::delete("DELETE FROM products WHERE product_id = ?", [$id]);
        return  $deleted
            ? response()->json(['message' => 'Product Deleted Succesfully'], 200)
            : response()->json(['error' => 'Not Found'], 404);
    }
}

