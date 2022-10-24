<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Repository\StudentRepoInterface;
use App\Repository\StudentRepoNeo4jInterface;
use Exception;
use Input;

class StudentController extends Controller
{
    //

    protected $studentRepo;
    protected $studentRepoNeo4j;

    public function __construct(StudentRepoInterface $studentRepo, StudentRepoNeo4jInterface $studentRepoNeo4j)
    {
        $this->studentRepo = $studentRepo;
        $this->studentRepoNeo4j = $studentRepoNeo4j;
    }

    public function index()
    {

        $student = $this->studentRepo->all();

        return response()->json([
            'status' => true,
            'data' => $student,
            'message' => 'query OK',
        ]);
    }


    public function store(StoreStudentRequest $request)
    {
        try {
            $validated = $request->validated();
           return $student = $this->studentRepoNeo4j->save($request->name, $request->subject, $request->date);
            // $student = $this->
            // $student = new Student();
            // $student->name     = $request->name;
            // $student->subject    = $request->subject;
            // $student->date = $request->date;

            // $student->save();
            return redirect()->back();
        } catch (Exception $e) {

            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
