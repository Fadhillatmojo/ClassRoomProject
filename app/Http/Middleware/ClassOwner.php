<?php

namespace App\Http\Middleware;

use App\Models\ClassRoom;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ClassOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentUser = Auth::user();
        $class = ClassRoom::findOrFail($request->classid);
        if ($currentUser->id != $class->teacher_id) {
            return response()->json(['message'=>'Data Not found'], 404);
        }
        return $next($request);
    }
}
