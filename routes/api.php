<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\UserController;
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

Route::middleware(['auth:sanctum'])->group(function () {
	Route::get('/me', [UserController::class, 'me']);
	Route::post('/logout', [UserController::class, 'logout']);
    // melihat semua class yang ada
    Route::get('/classes', [ClassRoomController::class, 'index']);
    Route::get('/classes/{id}', [ClassRoomController::class, 'show']);
    
    // ini acces untuk middleware teacher untuk akses 
    Route::middleware(['teacher-access'])->group(function () {
        Route::post('/classes', [ClassRoomController::class, 'store']);
        Route::middleware(['class-owner'])->group(function () {
            // hanya pemilik kelas yang bisa menghapus dan mengupdate kelas
            Route::patch('/classes/{classid}', [ClassRoomController::class, 'update']);
            Route::delete('/classes/{classid}', [ClassRoomController::class, 'destroy']);
            // hanya pemilik kelas yang dapat melihat daftar semua siswa di kelasnya
            Route::get('/classes/{classid}', [ClassRoomController::class, 'showStudents']);
            
            // hanya pemilik kelas lah yang dapat melihat semua assignments di dalamnya
            Route::get('/assignments', [AssignmentController::class, 'index']);
            // hanya pemilik kelas yang bisa menambahkan assignment di dalamnya
            Route::post('/assignments/{classid}', [AssignmentController::class, 'store']);
        });
        Route::middleware(['assignment-owner'])->group(function () {
            // pemilik assignment yang bisa show assignment 
            Route::get('/assignments/{assignmentid}', [AssignmentController::class, 'show']);
            // hanya pemilik kelas yang bisa update assignmentnya
            Route::patch('/assignments/{assignmentid}', [AssignmentController::class, 'update']);
            // hanya pemilik kelas yang bisa delete assignments
            Route::delete('/assignments/{assignmentid}', [AssignmentController::class, 'destroy']);
        });

    });

    // ini acces untuk middleware student untuk akses 
    Route::middleware(['student-access'])->group(function () {
	    Route::post('/classes/{id}', [ClassRoomController::class, 'followClass']);

        // ini middleware untuk check apakah dia sudah follow class itu atau belom
        Route::middleware(['followed-class-check'])->group(function () {
            // hanya yang sudah follow kelaslah yang bisa liat assignment dalam kelas tersebut
            Route::get('/assignments', [AssignmentController::class, 'index']);
            // hanya yang sudah follow kelaslah yang bs show assignment secara detail
            Route::get('/assignments/{id}', [AssignmentController::class, 'show']);
        });
    });

});

Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
