<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role = null)
    {
        $userId = $request->route('id'); // الحصول على ID المستخدم من الرابط

        // تحقق إذا كان المستخدم الحالي هو نفس المستخدم أو هو مسؤول
        if (auth()->user()->id != $userId && auth()->user()->role != 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403); // أو أي رسالة خطأ أخرى
        }

        return $next($request);
    }

    
}
