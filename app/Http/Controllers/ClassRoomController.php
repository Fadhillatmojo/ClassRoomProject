<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentsClassResource;
use Exception;
use App\Models\User;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ClassRoomResource;
use App\Http\Requests\StoreClassRoomRequest;
use App\Http\Requests\UpdateClassRoomRequest;

class ClassRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = ClassRoom::get();
        return ClassRoomResource::collection($classes->loadMissing('teacher:id,username'));
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'=>'required|max:50',
            'description'=> 'required|max:100'
        ]);
        $request['teacher_id'] = Auth::user()->id;
        $class = ClassRoom::create($request->all());
        return new ClassRoomResource($class->loadMissing('teacher:id,username'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $class = ClassRoom::with('teacher:id,username')->findOrFail($id);
        return new ClassRoomResource($class);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClassRoom $classRoom)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'=>'required|max:50',
            'description'=> 'required|max:100'
        ]);
        $class = ClassRoom::findOrFail($id);
        $class->update($request->all());
        return new ClassRoomResource($class);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try{
            $class = ClassRoom::findOrFail($id);
            $class->delete();
            return response()->json([
                "message" => "Data Deleted"
            ]);
        }catch(Exception $e){
            return response()->json([
                "error" => $e
            ],500);
        }
    }

    // get all students in class for class owner
    public function showStudents($id) {
        $studentsClass = ClassRoom::with('students:id,username')->findOrFail($id);
        return new StudentsClassResource($studentsClass);
    }

    // follow class fun for student
    public function followClass(Request $request) {
        // Mendapatkan instance siswa yang akan mengikuti kelas
        $student = User::find(auth()->user()->id); // Menyimpulkan bahwa siswa yang sedang masuk adalah yang akan mengikuti.
        // Mendapatkan instance kelas yang akan diikuti oleh siswa
        $classroom = Classroom::with('students')->findOrFail($request->class_id);
        // Menghubungkan siswa dengan kelas
        $classroom->students()->attach($student);
        // return message
        return response()->json(['message'=>'Class followed']);
    }


}
