<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role, // เพิ่มบรรทัดนี้: บันทึก Role ลงฐานข้อมูล
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // เช็ค Role เพื่อพาไปหน้า Dashboard ที่ถูกต้อง 
        if ($user->role === 'student') {
            return redirect()->route('student.dashboard'); // สมมติว่ามีเส้นทางนี้สำหรับนักเรียน
        } elseif ($user->role === 'teacher') {
            return redirect()->route('teacher.dashboard'); // สมมติว่ามีเส้นทางนี้สำหรับครู
        }
        return redirect(RouteServiceProvider::HOME);
    }

    /**
     * Display the student registration view.
     */
    public function createStudent(): View
    {
        return view('auth.register', ['role' => 'student']);
    }

    /**
     * Handle an incoming student registration request.
     */
    public function storeStudent(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'student',
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('student.dashboard');
    }

    /**
     * Display the teacher registration view.
     */
    public function createTeacher(): View
    {
        return view('auth.register', ['role' => 'teacher']);
    }

    /**
     * Handle an incoming teacher registration request.
     */
    public function storeTeacher(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'teacher',
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('teacher.dashboard');
    }
}
