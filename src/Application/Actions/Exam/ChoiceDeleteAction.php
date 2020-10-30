<?php
declare(strict_types=1);

namespace App\Application\Actions\Exam;

use App\Domain\Choice\ChoiceService;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class ChoiceDeleteAction extends ExamAction
{
    /**
     * {@inheritdoc}
     */
    private $choiceService;

    public function __construct(LoggerInterface $logger,ChoiceService $choiceService)
    {
      parent::__construct($logger);
      $this->choiceService = $choiceService;
    }

    protected function action(): Response
    {
      return $this->respondWithData($this->delete());
    }

    protected function delete()
    {
      return $this->choiceService->deleteChoice($this->request);
    }
}
