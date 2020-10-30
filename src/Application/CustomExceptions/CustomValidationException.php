<?php
namespace App\Application\CustomExceptions;

use Respect\Validation\Exceptions\ValidationException;

class CustomMessageValidationException extends ValidationException
{
    /**
     * @inheritDoc
     * @param string $message
     */
    public function __construct($validator, string $message, $response = null, $errorBag = 'default')
    {
        $this->message = $message;

        $this->response = $response;
        $this->errorBag = $errorBag;
        $this->validator = $validator;
    }
}