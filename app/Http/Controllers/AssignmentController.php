<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\ClassRoom;
use App\Models\Assignment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\AssignmentResource;
use App\Http\Requests\StoreAssignmentRequest;
use App\Http\Requests\UpdateAssignmentRequest;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assignments = Assignment::get();
        return AssignmentResource::collection($assignments);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        try{
            $validated = $request->validate([
                'title' => 'required|max:50',
                'description' => 'required|max:100',
                'due_date' => 'required|date',
                'class_id' => 'required'
            ]);
            $request['teacher_id'] = Auth::user()->id;
            $assignment = Assignment::create($request->all());
            return new AssignmentResource($assignment);
        }catch(Exception $e){
            return response()->json([
                'error' => $e
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try{
            $assignments = Assignment::findOrFail($id);
            return new AssignmentResource($assignments->loadMissing('teacher:id,username', 'classRoom:id,name'));
        }catch(Exception $e){
            return response()->json([
                'message' => 'not found'
            ],500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Assignment $assignment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $assignmentid)
    {
        try{
            $validated = $request->validate([
                'title' => 'required|max:50',
                'description' => 'required|max:100',
                'due_date' => 'required|date'
            ]);
            $assignment = Assignment::findOrFail($assignmentid);
            $assignment->update($request->all());
            return new AssignmentResource($assignment);
        } catch(Exception $e){
            return response()->json([
                'message' => 'not found'
            ],404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try{
            $assignment = Assignment::findOrFail($id);
            $assignment->delete();
            return response()->json(['message' => 'Data deleted']);
        }catch(Exception $e){
            return response()->json([
                'message' => 'not found'
            ],404);
        }
    }
}
