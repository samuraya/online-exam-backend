<?php
declare(strict_types=1);

namespace App\Domain\Subject;

interface SubjectRepositoryInterface
{
   
    public function findSubjectsByInstructor($instructorId);
}