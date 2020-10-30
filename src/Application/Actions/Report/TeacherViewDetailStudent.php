<?php
declare(strict_types=1);

namespace App\Application\Actions\Report;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Routing\RouteContext;

use App\Domain\Report\TeacherReportService;

class TeacherViewDetailStudent extends TeacherReportAction
{
    /**
     * {@inheritdoc}
     */
    // public function __construct(TeacherReportService $teacherReportService)
    // {
    //   $this->teacherReportService = $teacherReportService;
    // }

    protected function action(): Response
    {
       
        $reportStudent = $this
          ->teacherReportService
          ->reportDetailsOneStudent($this->request);
        

        return $this->respondWithData($reportStudent); 
       
    }
    
}
