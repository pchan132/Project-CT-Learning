<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StudentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // ตรวจสอบว่าเป็น student เท่านั้น
        if (!$user->isStudent()) {
            // ถ้าเป็น teacher ให้ redirect ไป teacher dashboard
            if ($user->isTeacher()) {
                return redirect()->route('teacher.dashboard');
            }
            // ถ้าเป็น role อื่นให้ redirect ไป dashboard ปกติ
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
