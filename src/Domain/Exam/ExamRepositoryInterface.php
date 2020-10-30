<?php
declare(strict_types=1);

namespace App\Domain\Exam;

interface ExamRepositoryInterface
{
    /**
     * @return Subject[]
     */
//public function findAll(): array;

    /**
     * @param int $id
     * @return User
     * @throws UserNotFoundException
     */
    public function findSubjectOfId(int $id): array;
}