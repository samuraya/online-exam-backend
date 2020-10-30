<?php
declare(strict_types=1);

namespace App\Application\Actions\Report;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Routing\RouteContext;

use App\Domain\Report\TeacherReportService;

class TeacherViewReportAllExams extends TeacherReportAction
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
       
        $reportByExam = $this
          ->teacherReportService
          ->reportCorrectWrongAll($this->request);
        

        return $this->respondWithData($questions); 
       
    }
    
}
