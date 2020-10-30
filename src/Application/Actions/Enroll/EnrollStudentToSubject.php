<?php
declare(strict_types=1);

namespace App\Application\Actions\Enroll;

//use App\Domain\Enroll\EnrollService;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class EnrollStudentToSubject extends EnrollStudentAction
{

	
 	protected function action(): Response
    {
       
        return $this->respondWithData($this->write());
    }

    protected function write()
    {
      return $this->enrollService->writeFromFile($this->request);
    }
}
