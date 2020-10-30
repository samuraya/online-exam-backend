<?php
declare(strict_types=1);

namespace App\Application\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class SessionMiddleware implements Middleware
{
    protected $options = [
            'name' => 'api-service',
            'lifetime' => 7200,
            'path' => null,
            'domain' => null,
            'secure' => false,
            'httponly' => true,
            'cache_limiter'=>'nocache',
        ];

     public function __construct($options = [])
    {
        $keys = array_keys($this->options);
        foreach ($keys as $key) {
            if (array_key_exists($key, $options)) {
                $this->options[$key] = $options[$key];
            }
        }
    }


    public function process(Request $request, RequestHandler $handler): Response
    {   
        
        if (session_status() == PHP_SESSION_ACTIVE) {
            // exit('session already started');
            return $handler->handle($request);
        }
        
        $options = $this->options;

        $current = session_get_cookie_params();

        $lifetime = (int)($options['lifetime'] ?: $current['lifetime']);

        $path     = $options['path'] ?: $current['path'];
        $domain   = $options['domain'] ?: $current['domain'];
        $secure   = (bool)$options['secure'];
        $httponly = (bool)$options['httponly'];



        session_set_cookie_params($lifetime, $path, $domain, $secure, $httponly);
        session_name($options['name']);
        session_cache_limiter($options['cache_limiter']);

        session_start();

        return $handler->handle($request);
        /* authentication via Headers******************************
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            
            session_start();
           // var_dump($_SERVER);
            $request = $request->withAttribute('session', $_SESSION);
        }

        return $handler->handle($request);
        *****************************************/
    }


   
}
