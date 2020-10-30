<?php
declare(strict_types=1);

namespace App\Application\Actions\Exam;

use App\Domain\Question\QuestionService;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class QuestionWriteAction extends ExamAction
{
    /**
     * {@inheritdoc}
     */
    private $questionService;

    public function __construct(LoggerInterface $logger,QuestionService $questionService)
    {
      parent::__construct($logger);
      $this->questionService = $questionService;
    }

    protected function action(): Response
    {
      
        return $this->respondWithData($this->write());
    }

    protected function write()
    {
      return $this->questionService->writeToQuestion($this->request);
    }
}
