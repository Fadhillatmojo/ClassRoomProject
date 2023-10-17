<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AssignmentOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // $assignmentId = $request->route('id');
        $currentUser = Auth::user();
        $assignment = Assignment::findOrfail($request->assignmentid);
        if ($currentUser->id != $assignment->teacher_id) {
            return response()->json(['message'=>'Data Not found'], 404);
        }
        // dd($assignment);
        return $next($request);
    }
}
