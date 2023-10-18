<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Submission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\SubmissionResource;
use App\Http\Requests\StoreSubmissionRequest;
use App\Http\Requests\UpdateSubmissionRequest;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $submissions = Submission::get();
            return SubmissionResource::collection($submissions);
        }catch(Exception $e){
            return response()->json([
                'message' => 'not found'
            ],404);
        }
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
        try{
            // return $request->file;
            $validated = $request->validate([
                'file' => 'required',
                'text' => 'required',
            ]);
            if ($request->file) {
                $fileName = $this->generateRandomString();
                $extension = $request->file->extension();

                Storage::putFileAs('files', $request->file, $fileName.'.'.$extension);
            }
            $request['student_id'] = Auth::user()->id;
            $request['file']= $fileName.'.'.$extension;
            $submission = Submission::create($request->all());

            return new SubmissionResource($submission);
        }catch(Exception $e){
            return response()->json([
                'message' => $e
            ],404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Submission $submission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Submission $submission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubmissionRequest $request, Submission $submission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Submission $submission)
    {
        //
    }
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
