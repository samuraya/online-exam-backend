<?php
declare(strict_types=1);

namespace App\Application\Actions\Profile;

use App\Application\Actions\Action;
//use App\Domain\Profile\ProfileRepository;
use App\Domain\Profile\{ProfileService, Profile};
use Psr\Log\LoggerInterface;

abstract class ProfileAction extends Action
{
    /**
     * @var UserRepository
     */
    //protected $userRepository;
    protected $profileService;

    /**
     * @param LoggerInterface $logger
     * @param UserRepository  $userRepository
     */
    public function __construct(LoggerInterface $logger,
        ProfileService $profileService)
    {
        parent::__construct($logger);
        //$this->userRepository = $userRepository;
        $this->profileService = $profileService;
    }
}
