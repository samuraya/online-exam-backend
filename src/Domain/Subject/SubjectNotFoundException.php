<?php
declare(strict_types=1);

namespace App\Domain\Subject;

use App\Domain\DomainException\DomainRecordNotFoundException;

class SubjectNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'There are no subjects under your name.';
}
