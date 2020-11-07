<?php
declare(strict_types=1);

namespace App\Application\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class ValidationMiddleware implements Middleware
{
     /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandler $handler): Response
    {

    	$id = $request->getParsedBody()['user_id'] ?? null;
    	$password = $request->getParsedBody()['password'] ?? null;
        $isValid = false;
        $errors = '';
        $passReg=  '/^(?=.*\d)(?=.*[a-z])(?=.*[^a-zA-Z0-9])(?!.*\s).{5,15}$/';


        if (!$id && !$password) {
        	
            $errors.='Please provide id and password!';
        }

        if(strlen((string)$id) < 8 || !is_numeric($id)) {
        	
            $errors.='Please make sure id is at least 8 digits long!';
        }
    
        if(preg_match($passReg, $password)===0){
        	
            $errors.= "Password must be between 5 to 15 characters which contain at least one numeric digit and a specialcharacter!";
        }
       
        $request = $request->withAttribute('validation', $errors); 

        return $handler->handle($request);
    }
}
