<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'service_id' => 'required|exists:services,id',
        'booking_date' => 'required|date',
    ]);

    $booking = Booking::create([
        'user_id' => auth()->id(),
        'service_id' => $request->service_id,
        'booking_date' => $request->booking_date,
    ]);

    return response()->json($booking, 201);
}

public function index()
{
    return response()->json(auth()->user()->bookings);
}



public function update(Request $request, $id)
{
    // تحقق من التوثيق
    // $user = auth()->user();
    // if (!$user) {
    //     return response()->json(['message' => 'User not authenticated'], 401);
    // }

    $booking = Booking::find($id);

    // تحقق إذا كان الـ booking موجودًا وإذا كان المستخدم هو المالك
    // if (!$booking || $booking->user_id !== $user->id) {
    //     return response()->json(['message' => 'Booking not found or you are not authorized to edit it'], 403);
    // }

  // السماح فقط لصاحب الـ Booking أو الأدمن
  if (auth()->user()->id !== $booking->user_id && auth()->user()->role !== 'admin') {
    return response()->json(['message' => 'Unauthorized'], 403);
}



    // تحقق من المدخلات
    $request->validate([
        'service_id' => 'required|exists:services,id',
        'booking_date' => 'required|date',
        //'status' => 'string',
    ]);

    $booking->update([
        'service_id' => $request->service_id,
        'booking_date' => $request->booking_date,
        'status' => $request->status,
    ]);

    return response()->json(['message' => 'Booking updated successfully', 'booking' => $booking], 200);
}


public function destroy($id)
{
    $booking = Booking::find($id);

    // تحقق إن الـ booking موجود والـ user هو مالكه
    // if (!$booking || $booking->user_id !== auth()->id()) {
    //     return response()->json(['message' => 'Booking not found or you are not authorized to delete it'], 403);
    // }

    $booking->delete();

    return response()->json(['message' => 'Booking deleted successfully'], 200);
}



}