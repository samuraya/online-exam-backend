<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Acl\DbTable as DbAuth;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Routing\RouteContext;

class ViewUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     */

    protected function action(): Response
    {
        return $this->respondWithData($this->viewUser());       
    }

    protected function viewUser() 
    {
        return $this->userService->retrieveUserBySession();
    }
}
