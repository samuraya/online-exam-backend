<?php
declare(strict_types=1);

namespace App\Domain\Question;

use App\Domain\DomainException\DomainRecordNotFoundException;

class QuestionNotFoundException extends DomainRecordNotFoundException
{
    public $message = "The question you requested doesn't exist.";
}
