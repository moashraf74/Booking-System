<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Models\Business;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public function store(Request $request)
    {
        // التحقق من المدخلات
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // التحقق من الصورة
            'image_url' => 'nullable|url' // التحقق من رابط الصورة
        ]);

        $imagePath = null;

        // إذا تم رفع صورة من الجهاز
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('businesses', 'public');
        }

        // إذا تم تقديم رابط صورة من الإنترنت
        if ($request->filled('image_url')) {
            $imageUrl = $request->image_url;
            $imageContents = Http::get($imageUrl);  // تحميل الصورة من الإنترنت

            // الحصول على اسم الصورة
            $imageName = basename($imageUrl);

            // حفظ الصورة في الـ public folder
            $imagePath = Storage::disk('public')->put('businesses/'.$imageName, $imageContents->body());
        }

        // إنشاء سجل جديد في الجدول
        $business = Business::create([
            'name' => $request->name,
            'location' => $request->location,
            'description' => $request->description,
            'image' => $imagePath, // حفظ مسار الصورة
        ]);

        return response()->json($business, 201);
    }

    public function index()
    {
        // عرض جميع الأعمال
        return response()->json(Business::all());
    }

    public function update(Request $request, $id)
    {
        $business = Business::findOrFail($id);

        // تحقق من المدخلات
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // التحقق من الصورة
            'image_url' => 'nullable|url' // التحقق من رابط الصورة
        ]);

        $imagePath = $business->image;

        // إذا كانت هناك صورة جديدة، ارفعها واحذف القديمة
        if ($request->hasFile('image')) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('businesses', 'public');
        }

        // إذا كان هناك رابط لصورة من الإنترنت
        if ($request->filled('image_url')) {
            $imageUrl = $request->image_url;
            $imageContents = Http::get($imageUrl);  // تحميل الصورة من الإنترنت

            // الحصول على اسم الصورة
            $imageName = basename($imageUrl);

            // حفظ الصورة في الـ public folder
            $imagePath = Storage::disk('public')->put('businesses/'.$imageName, $imageContents->body());
        }

        // تحديث البيانات
        $business->update([
            'name' => $request->name,
            'location' => $request->location,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return response()->json(['message' => 'Business updated successfully', 'business' => $business], 200);
    }

    public function destroy($id)
    {
        $business = Business::findOrFail($id);

        // حذف الصورة إذا كانت موجودة
        if ($business->image) {
            Storage::disk('public')->delete($business->image);
        }

        // حذف السجل
        $business->delete();

        return response()->json(['message' => 'Business deleted successfully'], 200);
    }
}

