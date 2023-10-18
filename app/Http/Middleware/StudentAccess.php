<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StudentAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try{
            $currentUser = Auth::user();
            if($currentUser->role == "teachers"){
                return response()->json([
                    'message' => 'unauthorized'
                ], 404);
            }
            return $next($request);
        } catch(Exception $e){
            return response()->json([
                'message' => 'unauthorized'
            ],404);
        }
    }
}
