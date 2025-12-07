<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRequest;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{

   public function index(): JsonResponse
{
    $students = User::where('role', 'student')
        ->with( 'studentProfile')
        ->get();

    if ($students->isEmpty()) {
        return response()->json([
            'message' => 'Students not found'
        ], 404);
    }

    return response()->json([
        'students' => $students
    ], 200);
}


    public function store(StudentRequest $request)
    {
        $studentValidate = $request->validated();


        $userData = collect($studentValidate)->only(['name','surname','email','phone','password'])->toArray();
        if(isset($userData['password'])){
            $userData['password'] = Hash::make($userData['password']);
    }

        $student = User::create($userData);

           $profileData = $request->validated();
           $profile = collect($profileData)->only(['faculty','group_name','course','tutor','gender','living_type','rent_address'])->toArray();
          if($profile){
            $profile['user_id'] = $student->id;
            // dd(vars: $profile);
            StudentProfile::create($profile);
        }

        return response()->json([
            'success'=> true
        ],200);

    }

    public function show(string $id): JsonResponse
{
    $student = User::where('role', 'student')
        ->where('id', $id)
        ->with('studentProfile')
        ->first();

    if (!$student) {
        return response()->json([
            'message' => 'Student not found'
        ], 404);
    }

    return response()->json([
        'student' => $student
    ], 200);
}

   
    public function update(StudentRequest $request, string $id)
    {
        $student = User::with('studentProfile')->find($id);

        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        $validated = $request->validated();

        $userData = collect($validated)->only(['name', 'surname', 'email', 'phone', 'password'])->toArray();

    if(isset($userData['password'])){
        $userData['password'] = Hash::make($userData['password']);
    }

    $student->update( $userData);

    $profileData = $request->validated('student_profile') ?? [];

    if (!empty($profileData)) {
        if ($student->studentProfile) {
            $student->studentProfile->update($profileData);
        } else {
            $profileData['user_id'] = $student->id;
            StudentProfile::create($profileData);
        }
    }
        return response()->json([
            'success' => true
        ],200);

    }


    public function destroy(string $id): JsonResponse
    {
        $student = User::with('studentProfile')->find( $id );

        if($student){
            $student->delete();
            return response()->json([
            'success'=> true
        ],200);
        }

        return response()->json([
            'error'=> 'Student not found'
        ],404);
    }
}
