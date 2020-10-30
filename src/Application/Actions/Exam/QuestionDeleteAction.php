<?php
declare(strict_types=1);

namespace App\Application\Actions\Exam;

use App\Domain\Question\QuestionService;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class QuestionDeleteAction extends ExamAction
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
      return $this->respondWithData($this->delete());
    }

    protected function delete()
    {
      return $this->questionService->deleteQuestion($this->request);
    }
}
