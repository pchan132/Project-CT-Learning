<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TeacherMiddleware
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
        
        // ตรวจสอบว่าเป็น teacher เท่านั้น
        if (!$user->isTeacher()) {
            // ถ้าเป็น student ให้ redirect ไป student dashboard
            if ($user->isStudent()) {
                return redirect()->route('student.dashboard');
            }
            // ถ้าเป็น role อื่นให้ redirect ไป dashboard ปกติ
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
