<?php
declare(strict_types=1);

namespace App\Application\Actions\Exam;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Routing\RouteContext;
use Psr\Log\LoggerInterface;

use App\Domain\Question\QuestionService;

class ViewExamAction extends ExamAction
{
    /**
     * {@inheritdoc}
     */
    public function __construct(LoggerInterface $logger,QuestionService $questionService)
    {
      parent::__construct($logger);
      $this->questionService = $questionService;
    }

    protected function action(): Response
    {
       
        $questions = $this->questionService
        ->fetchAllQuestionsByExam($this->args);
       
        //$this->logger->info("Users list was viewed.");

        return $this->respondWithData($questions); 
       
    }
    
}
