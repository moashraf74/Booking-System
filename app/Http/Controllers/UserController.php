<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function show($id)
    {
        $user = User::find($id); // البحث عن المستخدم حسب الـ id

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json(['user' => $user], 200);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id); // البحث عن المستخدم حسب الـ id

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // فحص الصلاحيات: يسمح فقط للمستخدم نفسه أو للمشرف بالتعديل
        if (auth()->user()->id !== $user->id && auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // تحقق من المدخلات
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        // تحديث البيانات
        $user->update($request->only('name', 'email'));

        return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
    }

    public function changePassword(Request $request, $id)
    {
        $user = User::find($id); // البحث عن المستخدم حسب الـ id

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // فحص الصلاحيات: يسمح فقط للمستخدم نفسه أو للمشرف بتغيير كلمة السر
        if (auth()->user()->id !== $user->id && auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // تحقق من المدخلات
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // تحقق من صحة كلمة السر الحالية
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 400);
        }

        // تغيير كلمة السر
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password changed successfully'], 200);
    }
}
