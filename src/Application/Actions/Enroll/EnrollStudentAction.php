<?php
declare(strict_types=1);

namespace App\Application\Actions\Enroll;

use App\Application\Actions\Action;
use App\Domain\Enroll\EnrollService;
use Psr\Log\LoggerInterface;

abstract class EnrollStudentAction extends Action
{
    /**
     * @var UserRepository
     */ 
    protected $enrollService;

    /**
     * @param LoggerInterface $logger
     * @param UserRepository  $userRepository
     */
    public function __construct(LoggerInterface $logger, EnrollService $enrollService)
    {
        parent::__construct($logger);
        $this->enrollService = $enrollService;
        
    }

   




}