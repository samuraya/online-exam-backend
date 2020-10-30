<?php
declare(strict_types=1);

namespace App\Application\Actions\Exam;

use App\Domain\Answer\AnswerService;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class AnswerWriteAction extends ExamAction
{
    /**
     * {@inheritdoc}
     */
    private $answerService;

    public function __construct(LoggerInterface $logger,AnswerService $answerService)
    {
      parent::__construct($logger);
      $this->answerService = $answerService;
    }

    protected function action(): Response
    {
      
        return $this->respondWithData($this->write());
    }

    protected function write()
    {
      return $this->answerService->writeToAnswer($this->request);
    }
}
