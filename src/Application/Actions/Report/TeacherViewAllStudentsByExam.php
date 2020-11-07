<?php
declare(strict_types=1);

namespace App\Application\Actions\Report;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Routing\RouteContext;

use App\Domain\Report\TeacherReportService;

class TeacherViewAllStudentsByExam extends TeacherReportAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
       
        $reportByExam = $this
          ->teacherReportService
          ->reportAllStudentsByExam($this->request);
        

        return $this->respondWithData($reportByExam); 
       
    }
    
}
