<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Domain\User\UserRepository;
use App\Domain\User\{UserService, User};
use Psr\Log\LoggerInterface;

abstract class UserAction extends Action
{
    /**
     * @var UserRepository
     */
    //protected $userRepository;
    protected $userService;

    /**
     * @param LoggerInterface $logger
     * @param UserRepository  $userRepository
     */
    public function __construct(LoggerInterface $logger,
        UserService $userService)
    {
        parent::__construct($logger);
        //$this->userRepository = $userRepository;
        $this->userService = $userService;
    }
}
