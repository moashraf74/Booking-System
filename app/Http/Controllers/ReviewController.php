<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'service_id' => 'required|exists:services,id',
        'rating' => 'required|integer|between:1,5',
        'comment' => 'nullable|string',
    ]);

    $review = Review::create([
        'user_id' => auth()->id(),
        'service_id' => $request->service_id,
        'rating' => $request->rating,
        'comment' => $request->comment,
    ]);

    return response()->json($review, 201);
}

public function index()
{
    return response()->json(Review::all());
}


public function update(Request $request, $id)
{
    $review = Review::find($id);

    // تحقق إن الـ review موجود والـ user هو مالكه
    // if (!$review || $review->user_id !== auth()->id()) {
    //     return response()->json(['message' => 'Review not found or you are not authorized to edit it'], 403);
    // }

 // السماح فقط لصاحب الـ Review أو الأدمن
 if (auth()->user()->id !== $review->user_id && auth()->user()->role !== 'admin') {
    return response()->json(['message' => 'Unauthorized'], 403);
}

    // تحقق من المدخلات
    $request->validate([
        'rating' => 'required|numeric|min:1|max:5',
        'comment' => 'nullable|string',
    ]);

    $review->update([
        'rating' => $request->rating,
        'comment' => $request->comment,
    ]);

    return response()->json(['message' => 'Review updated successfully', 'review' => $review], 200);
}


public function destroy($id){
    $review = Review::findOrFail($id);
    $review->delete();
    return response()->json("review deleted");
}



}


