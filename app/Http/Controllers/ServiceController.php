<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{


    public function store(Request $request)
{
    $request->validate([
        'business_id' => 'required|exists:businesses,id',
        'name' => 'required|string',
        'price' => 'required|numeric',
    ]);

    $service = Service::create($request->all());

    return response()->json($service, 201);
}


public function index()
{
    return response()->json(Service::all());
}


public function update(Request $request, $id)
{
    $service = Service::find($id);

    // تحقق إن الـ service موجود والـ user هو مالكه
    // if (!$service || $service->business->user_id !== auth()->id()) {
    //     return response()->json(['message' => 'Service not found or you are not authorized to edit it'], 403);
    // }

    // تحقق من المدخلات
    $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric',
        'business_id' => 'required',
    ]);

    $service->update([
        'name' => $request->name,
        'price' => $request->price,
        'business_id'=> $request->business_id,
    ]);

    return response()->json(['message' => 'Service updated successfully', 'service' => $service], 200);
}


public function destroy($id)
{
    $service = Service::find($id);

    // تحقق إن الـ service موجود والـ user هو مالكه
    // if (!$service || $service->business->user_id !== auth()->id()) {
    //     return response()->json(['message' => 'Service not found or you are not authorized to delete it'], 403);
    // }

    $service->delete();

    return response()->json(['message' => 'Service deleted successfully'], 200);
}







}
