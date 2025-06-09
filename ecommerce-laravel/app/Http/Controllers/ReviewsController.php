<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReviewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getAllReviews()
    {

        if(!auth()->user()){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $reviews = DB::select("SELECT * FROM reviews");
        return $reviews ? response()->json($reviews)
                        : response()->json(['message' => 'Reviews not found']);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function createReview(Request $request)
    {
        $user = auth()->user();

        if(!auth()->user()){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validate = Validator::make($request->all(), [
            'product_id' => 'integer|required|exists:products,product_id',
            'rating' => 'Integer|required|min:1|max:5',
            'comment' => 'required|string'
        ]);

        if($validate->fails()){
            return response()->json(['message' => 'Failed to Validate']);
        }

        $existingProduct = DB::selectOne(
            "SELECT * FROM reviews WHERE user_id = ? AND product_id = ?",
            [$user->user_id, $request->input('product_id')]);

        if($existingProduct){
            return response()->json(['message' => 'Review already Exist'], 409);
        }


        DB::insert("INSERT INTO reviews (user_id, product_id, rating, comment, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)",
        [
             $user->user_id,
             $request->input('product_id'),
             $request->input('rating'),
             $request->input('comment'),
             now(),
             now()

        ]);
        
        $reviews_id = DB::getPdo()->lastInsertId();

        $reviews = DB::selectOne("SELECT * FROM reviews WHERE review_id = ?", [$reviews_id]);
        return response()->json(['message' => 'Reviews Successfully Posted'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function getSpecificReview($review_id)
    {
        $user = auth()->user();

        $getReview = DB::selectOne("SELECT * FROM reviews WHERE review_id = ?", [$review_id]);
        if(!$getReview){
            return response()->json(['Message' => 'Invalid Review'], 404);
        }

        if(auth()->user()->isAdmin() && $user->user_id != $getReview->user_id){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $getReviewId = DB::selectOne("SELECT * FROM reviews WHERE review_id = ?", [$review_id]);
        return $getReviewId ? response()->json($getReviewId)
                            : response()->json(['message' => 'Review Not Found']);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function editReview(Request $request, $review_id)
    {
        $user = auth()->user();

        $getReview = DB::selectOne("SELECT * FROM reviews WHERE review_id = ?", [$review_id]);
        if(!$getReview){
            return response()->json(['message' => 'Invalid Reviews'], 404);
        }

        if(auth()->user()->isAdmin() && $user->user_id != $getReview->user_id){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validate = Validator::make($request->all(), [
            'rating' => 'integer|required|min:1|max:5',
            'comment' => 'required|string|max:500'
        ]);

        if($validate->fails()){
            return response()->json(['message' => 'Validation Failed'], 422);
        }

        $updatedReview = DB::update("UPDATE reviews SET 
                    rating = ?,
                    comment = ?,
                    updated_at = NOW()
                    WHERE review_id = ?", 
                    [
                    $request->input('rating'),
                    $request->input('comment'),
                    $review_id
                    ]);

        return $updatedReview ? response()->json($updatedReview)
                              : response()->json(['message' => 'Failed to Updated'], 404);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteReview($review_id)
    {
        $user = auth()->user();

        $getReviewId = DB::selectOne("SELECT * FROM reviews WHERE review_id = ?", [$review_id]);
        if(!$getReviewId){
            return response()->json(['message' => 'Reviews Not Found'], 404);
        }

        if(auth()->user()->isAdmin() && $getReviewId->user_id != $user->user_id){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $deleteId = DB::delete("DELETE FROM reviews where review_id = ?", [$review_id]);
        return $deleteId ? response()->json(['message' => 'Review Successfully Deleted'], 200)
                         : response()->json(['message' => 'No Review from this user'], 500);
    }
}
