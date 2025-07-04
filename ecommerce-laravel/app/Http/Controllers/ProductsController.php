<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


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
    public function addProduct(Request $request)
    {
        // ✅ Check authentication first
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized - Please login'], 401);
        }

        $user = auth()->user();
        $userId = $user->id; // ✅ Fix: Use 'id', not 'user_id'

        // ✅ Fix: Use || instead of &&
        if (!$user->isAdmin() && !$user->isSeller()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validation = Validator::make($request->all(), [
            'product_name' => 'required|string|max:100|unique:products,product_name',
            'product_description' => 'required|string|max:200',
            'product_price' => 'required|numeric|min:0|max:9999999',
            'product_stock' => 'required|numeric|min:0|max:100000',
            'product_image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif|max:2048',
            // ✅ Accept both field names from frontend
            'category_id' => 'required|integer|exists:product_category,product_category_id',
            'product_category_id' => 'nullable|integer|exists:product_category,product_category_id'
        ]);

        if($validation->fails()){
            return response()->json([
                'Message' => 'Validation Failed',
                'Errors' => $validation->errors()
            ], 422);
        }

        $imagePath = null;
        if($request->hasFile('product_image')){
            $image = $request->file('product_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('products', $imageName, 'public');
        }

        $categoryId = $request->input('category_id') ?? $request->input('product_category_id');

        DB::insert("INSERT INTO products(product_name, product_description, product_price, product_stock, image_url, category_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", [
            $request->input('product_name'),
            $request->input('product_description'),
            $request->input('product_price'),
            $request->input('product_stock'),
            $imagePath,
            $categoryId,
            now(),
            now()
        ]);

        $getLastId = DB::getPdo()->lastInsertId();

        $getProducts = DB::selectOne("SELECT * FROM products WHERE product_id = ?", [$getLastId]);
        return $getProducts ? response()->json([
            'message' => 'Product added successfully',
            'product' => $getProducts
        ], 201) : response()->json([
            'message' => 'Product Failed to Add'
        ], 500);
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

