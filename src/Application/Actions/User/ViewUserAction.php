<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Acl\DbTable as DbAuth;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Routing\RouteContext;


//use App\Domain\User\{UserService, User};


class ViewUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     */

    protected function action(): Response
    {
       exit("view user action controller");
        $this->logger->info("single user was viewed.");
        return $this->respondWithData($this->viewUser());       
    }

    protected function viewUser() 
    {
        return $this->userService->retrieveUserBySession();
    }
}
