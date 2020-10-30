<?php
declare(strict_types=1);

namespace App\Application\Actions\Exam;

use App\Domain\Subject\SubjectService;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class ListSubjectsAction extends ExamAction
{
    /**
     * {@inheritdoc}
     */
    private $subjectService;

    public function __construct(LoggerInterface $logger,SubjectService $subjectService)
    {
      parent::__construct($logger);
      $this->subjectService = $subjectService;
    }

    protected function action(): Response
    {
      
        $subjects = $this->subjectService->listOfSubjects();
          

        return $this->respondWithData($subjects);
    }
}
