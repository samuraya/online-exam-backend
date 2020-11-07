<?php
declare(strict_types=1);

namespace App\Application\Actions\Exam;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Routing\RouteContext;
use Psr\Log\LoggerInterface;
use App\Domain\Choice\ChoiceService;

class ViewQuestionAction extends ExamAction
{
    /**
     * {@inheritdoc}
     */
    public function __construct(LoggerInterface $logger,ChoiceService $choiceService)
    {
      parent::__construct($logger);
      $this->choiceService = $choiceService;
    }

    protected function action(): Response
    {
       
        $choices = $this->choiceService
        ->fetchAllChoicesByQuestion($this->args);
       
        return $this->respondWithData($choices); 
       
    }
    
}
