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

        // บังคับ role เป็น student เสมอ (Teacher ต้องให้ Admin สร้างให้)
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
     * ปิดการลงทะเบียน Teacher - ให้ Admin สร้างให้เท่านั้น
     */
    public function createTeacher(): RedirectResponse
    {
        return redirect()->route('register')->with('warning', 'การลงทะเบียนเป็นอาจารย์ต้องติดต่อผู้ดูแลระบบ');
    }

    /**
     * Handle an incoming teacher registration request.
     * ปิดการลงทะเบียน Teacher - ให้ Admin สร้างให้เท่านั้น
     */
    public function storeTeacher(Request $request): RedirectResponse
    {
        return redirect()->route('register')->with('warning', 'การลงทะเบียนเป็นอาจารย์ต้องติดต่อผู้ดูแลระบบ');
    }
}
