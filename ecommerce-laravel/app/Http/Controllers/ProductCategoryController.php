<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getAllCategory(Request $request)
    {
        if(!auth()->user()->isAdmin() && !auth()->user()->isSeller()){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        //Get page number from request, default to 1
        $page = $request->input('page', 1);
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        // get total count
        $totalCount = DB::selectOne("SELECT COUNT(*) as total FROM product_category")->total;

        // get pagination info
        $categories = DB::select("SELECT * FROM product_category ORDER BY created_at DESC LIMIT ? OFFSET ?",[$perPage, $offset]);

        // Calculate pagination info
        $totalPages = ceil($totalCount / $perPage);
        $hasNextPage = $page < $totalPages;
        $hasPreviousPage = $page > 1;

        // 🔍 Add debugging info
        \Log::info("API Debug - Page: {$page}, PerPage: {$perPage}, Offset: {$offset}, Total: {$totalCount}, Retrieved: " . count($categories));

        return response()->json([
            'data' => $categories,
            'pagination' => [
                'current_page' => (int)$page,
                'per_page' => $perPage,
                'total' => $totalCount,
                'total_pages' => $totalPages,
                'has_next_page' => $hasNextPage,
                'has_previous_page' => $hasPreviousPage,
                'next_page' => $hasNextPage ? $page + 1 : null,
                'previous_page' => $hasPreviousPage ? $page - 1 : null
            ],
            // 🔍 Temporary debug info
            'debug' => [
                'page_requested' => $page,
                'offset_calculated' => $offset,
                'total_in_db' => $totalCount,
                'categories_returned' => count($categories),
                'first_category_id' => !empty($categories) ? $categories[0]->product_category_id : null,
                'last_category_id' => !empty($categories) ? end($categories)->product_category_id : null
            ]
        ], 200);
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
        $exists = DB::selectOne("SELECT * FROM product_category WHERE name = ?", [$request->input('name')]);
        if($exists){
            return response()->json(['message' => 'Category already exists'], 409);
        }

        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'description' => 'required|max:150',
            'status' => 'required'
        ]);

        if($validation->fails()){
            return response()->json(['message' => 'validation failed', $validation->errors()], 422);
        }

        DB::insert("INSERT INTO product_category (name, description, status, created_at, updated_at) VALUES(?, ?, ?, ?, ?) ",
            [
                $request->input('name'),
                $request->input('description'),
                $request->input('status'),
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
        if(!auth()->user()->isSeller() && !auth()->user()->isAdmin()){
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    
        $selectCategory = DB::selectOne("SELECT * FROM product_category WHERE product_category_id = ?", [$product_category_id]);
        return $selectCategory ? response()->json($selectCategory, 200)
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


    public function toggleCategoryStatus($product_category_id)
        {
            $admin = auth()->user()->isAdmin();
            $seller = auth()->user()->isSeller();

            if (!$admin && !$seller) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $category = DB::selectOne("SELECT * FROM product_category WHERE product_category_id = ?", [$product_category_id]);

            if (!$category) {
                return response()->json(['message' => 'Product Category Not Found'], 404);
            }

            $newStatus = ($category->status === 'active') ? 'inactive' : 'active';

            $affectedRows = DB::update(
                "UPDATE product_category SET status = ?, updated_at = ? WHERE product_category_id = ?",
                [$newStatus, now(), $product_category_id]
            );

            if ($affectedRows === 0) {
                return response()->json(['message' => 'Failed to update status'], 500);
            }

            $updatedQuery = DB::selectOne("SELECT * FROM product_category WHERE product_category_id = ?", [$product_category_id]);

            return response()->json([
                'message' => "Category status changed to {$newStatus}",
                'category' => $updatedQuery
            ], 200);
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
