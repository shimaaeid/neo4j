<?php

namespace App\Repository;

use App\Models\Student;
use App\Repository\StudentRepoInterface;

class StudentRepoClass implements StudentRepoInterface{

    // protected $studentRepo;

    // public function __construct(StudentRepoInterface $studentRepo)
    // {
    //     $this->studentRepo = $studentRepo;

    // }

    public function all():array{

        // return "fgdfgd";

        $student = [
            new Student([
                'name' => 'shimaa eid',
                'subject' => 'math',
                'date' => now()
            ])
            ];
            return $student;
    }

}
