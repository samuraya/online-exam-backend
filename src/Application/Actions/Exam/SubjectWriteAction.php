<?php
declare(strict_types=1);

namespace App\Application\Actions\Exam;

use App\Domain\Subject\SubjectService;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class SubjectWriteAction extends ExamAction
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
      
        return $this->respondWithData($this->write());
    }

    protected function write()
    {
      return $this->subjectService->writeToSubject($this->request);
    }
}
