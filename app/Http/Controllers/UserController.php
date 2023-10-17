<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    function login(Request $request) {
        try{
            // validasi input
            $request->validate([
                'email'=>'required|email',
                'password'=>'required'
            ]);

            // ngecek apakah usernya itu ada atau gak
            $user = User::where('email', $request->email)->first();
            
            // ngecek apakah usernya itu passwordnya bener atau salah, kalau salah, dia bakal throw exception
            if (! $user || ! Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email'=>['The provided credentials are incorrect.']
                ]);
            }

            return $user->createToken($request->email)->plainTextToken;

        }catch(Exception $e){
            return response()->json([
                'error' => $e
            ]);
        }
        
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|max:50',
            'email' => 'required|email',
            'password' => 'required',
            'role' => ['required', 'in:teacher,student'],
        ]);
   
        try{
            $user = User::create([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
            ]);
            return response()->json([
                'username' => $user->username,
                'message' => 'Success'
            ]);
        }catch(Exception $e){
            return response()->json([
                'error' => $e
            ]);
        } 
    }

    public function logout(Request $request)
    {
        try{
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message'=>'Logout Success']);
        }catch(Exception $e){
            return response()->json([
                "error" => $e
            ]);
        }
    }

    function me(){
        return response()->json(Auth::user());
    }

}
