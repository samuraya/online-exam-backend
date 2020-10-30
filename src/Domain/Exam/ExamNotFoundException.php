<?php
declare(strict_types=1);

namespace App\Domain\Exam;

use App\Domain\DomainException\DomainRecordNotFoundException;

class ExamNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The exam you requested does not exist.';
}
