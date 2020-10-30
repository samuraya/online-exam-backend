<?php
declare(strict_types=1);

namespace App\Application\Actions\Exam;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Exam\ExamListRepository;
//use Psr\Log\LoggerInterface;

abstract class ExamAction extends Action
{
    /**
     * @var UserRepository
     */ 
    //protected $examListRepository;

    /**
     * @param LoggerInterface $logger
     * @param UserRepository  $userRepository
     */
    // public function __construct(LoggerInterface $logger)
    // {
    //     //parent::__construct($logger);
    //     //$this->examListRepository = $examListRepository;
    // }
}