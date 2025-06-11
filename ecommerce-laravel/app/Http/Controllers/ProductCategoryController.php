<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getAllCategory()
    {
        if(!auth()->user()->isSeller() && !auth()->user()->isSeller()){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $categories = DB::select("SELECT * product_category");
        return $categories ? response()->json($categories, 200)
                           : response()->json(['message' => ''], 500);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function addProductCategory(Request $request)
    {
        $seller = auth()->user()->isSeller();
        $admin = auth()->user()->isAdmin();

        if(!$seller && !$admin){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:50'
        ]);

        if($validation->fails()){
            return response()->json(['message' => 'validation failed', $validation->errors()], 422);
        }

        DB::insert("INSERT INTO product_category (name, create_at, updated_at) VALUES(?, ?, ?) ",
            [
                $request->input('name'),
                now(),
                now()
            ]);

        $lastId = DB::getPdo()->lastInsertId();
        $getCategory = DB::selectOne("SELECT * FROM product_category WHERE product_category_id = ?", [$lastId]);
        return $getCategory ? response()->json([$getCategory], 200)
                            : response()->json(['message' => 'Category not Found'], 404);
    }

    /**
     * Display the specified resource.
     */
    public function getSpecificCategory($product_category_id)
    {
        if(auth()->user()->isSeller && auth()->user()->isAdmin()){
            return response()->json(['message' => 'Unauthorize'], 401);
        }
    
        $selectCategory = DB::selectOne("SELECT * FROM product_category WHERE product_category_id = ?", [$product_category_id]);
        return $selecCategory ? response()->json($selectCategory, 200)
                              : response()->json(['message' => 'Failed to fetch product'], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function editCategory(Request $request, $product_category_id)
    {
        $admin = auth()->user()->isAdmin();
        $seller = auth()->user()->isSeller();

        if(!$admin && !$seller){
            return response()->json(['message' => 'unauthorized'], 401);
        }

        $validation = Validator::make($request->all(), [

            'name' => 'string|max:50|required'
        ]);

        if($validation->fails()){
            return response()->json(['message' => 'failed to validate', 'error' => $validation->errors()], 422);
        }

        $affectedRows = DB::update("UPDATE product_category SET name = ? , updated_at = ? WHERE product_category_id = ?", 
        [
            $request->input('name'),
            now(),
            $product_category_id
        ]);

        if($affectedRows === 0){
            return response()->json(['message' => 'No Changes'], 404);
        }
        
        $updatedRow = DB::selectOne("SELECT * FROM product_category WHERE product_category_id = ?", [$product_category_id]);
        return response()->json($updatedRow, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteCategory($product_category_id)
    {
        if(!auth()->user()->isSeller() && !auth()->user()->isAdmin()){
            return response()->json(['message' => 'Unauthorized'], 402);
        }

        $deleteId = DB::delete("DELETE FROM product_category WHERE product_category_id = ?", [$product_category_id]);
        return $deleteId ? response()->json($deleteId, 200)
                         : response()->json(['message' => 'Failed to delete'], 500);
    }
}
