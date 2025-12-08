<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'database' => \Illuminate\Support\Facades\DB::connection()->getPdo() ? 'connected' : 'disconnected',
        'cache' => \Illuminate\Support\Facades\Cache::store('file')->get('health_check') ? 'working' : 'working', // Simple check
        'storage' => is_writable(storage_path()) ? 'writable' : 'not_writable',
    ]);
});

Route::get('/debug-cert/{id}', function ($id) {
    $certificate = \App\Models\Certificate::with(['course.teacher', 'student', 'template'])->findOrFail($id);
    return [
        'certificate' => $certificate,
        'teacher' => $certificate->course->teacher,
        'template' => $certificate->template ?? \App\Models\CertificateTemplate::getActiveTemplate(),
        'teacher_name' => optional($certificate->course->teacher)->name,
        'admin_name' => $certificate->template->admin_name ?? 'Default Admin',
    ];
});
