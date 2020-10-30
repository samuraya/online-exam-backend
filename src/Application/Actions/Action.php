<?php
declare(strict_types=1);

namespace App\Application\Actions;

use App\Domain\DomainException\DomainRecordNotFoundException;
use App\Domain\DomainException\DomainUnauthorizedException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;

use Slim\Views\PhpRenderer;

abstract class Action
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var array
     */
    protected $args;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
       
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     * @throws HttpNotFoundException
     * @throws HttpBadRequestException
     */
    public function __invoke(Request $request, Response $response, $args): Response
    {
       
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;
        
        try {
            return $this->action();
        } catch (DomainRecordNotFoundException $e) {
            throw new HttpNotFoundException($this->request, $e->getMessage());
        } catch (DomainUnauthorizedException $e) {
            throw new HttpUnauthorizedException($this->request, $e->getMessage());       
        }
    }

    /**
     * @return Response
     * @throws DomainRecordNotFoundException
     * @throws HttpBadRequestException
     */
    abstract protected function action(): Response;

    /**
     * @return array|object
     * @throws HttpBadRequestException
     */
    protected function getFormData()
    {
        $input = json_decode(file_get_contents('php://input'));

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new HttpBadRequestException($this->request, 'Malformed JSON input.');
        }

        return $input;
    }

    /**
     * @param  string $name
     * @return mixed
     * @throws HttpBadRequestException
     */
    protected function resolveArg(string $name)
    {
        if (!isset($this->args[$name])) {
            throw new HttpBadRequestException($this->request, "Could not resolve argument `{$name}`.");
        }

        return $this->args[$name];
    }

    /**
     * @param  array|object|null $data
     * @return Response
     */
    protected function respondWithData($data = null): Response
    {
      
        $statusCode = $data['status_code'] ?? 200;
        
        unset($data['status_code']);
        $payload = new ActionPayload($statusCode, $data);
        return $this->respond($payload,$statusCode);
    }

    /**
     * @param ActionPayload $payload
     * @return Response
     */

    protected function respond(ActionPayload $payload, $statusCode): Response
    {
        $json = json_encode($payload, JSON_PRETTY_PRINT);
    
        //$this->logger->info("MB memory used: ".memory_get_peak_usage()/1000000);
        $this->response->getBody()->write($json);
    
        return $this->response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
    }

/***************PHP rendered pages*****************************
     protected function respond(ActionPayload $payload, $statusCode): Response
    {
       // include "templates/home.phtml";
        $renderer = new PhpRenderer('templates');
       return $renderer->render($this->response, "hello.php",["name"=>"John"]);
       // $this->response->getBody()->write($template);
    
        //return $this->response->withHeader('Content-Type', 'text/html')->withStatus($statusCode);
    }
*****************************************************************/





}
