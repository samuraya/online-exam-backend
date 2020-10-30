<?php
declare(strict_types=1);

namespace App\Application\Actions\Exam;

use App\Domain\Exam\ExamService;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class CloseExamAction extends ExamAction
{
    /**
     * {@inheritdoc}
     */
    private $examService;

    public function __construct(LoggerInterface $logger,ExamService $examService)
    {
      parent::__construct($logger);
      $this->examService = $examService;
    }

    protected function action(): Response
    {
      return $this->respondWithData($this->close());
    }

    protected function close()
    {
      return $this->examService->closeExam($this->request);
    }
}
