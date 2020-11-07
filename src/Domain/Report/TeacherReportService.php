<?php
declare(strict_types=1);

namespace App\Domain\Report;

use Psr\Http\Message\ServerRequestInterface as Request;

use App\Infrastructure\Persistence\Report\ReportRepository;
use App\Domain\DomainException\DomainBadRequestException;

final class TeacherReportService 
{
	
	private $reportRepository;
	 

	public function __construct(ReportRepository $reportRepository)
	{
		$this->reportRepository = $reportRepository;
	}



	public function reportAllStudentsByExam(Request $request)
	{
 				
		$examId = $request->getAttribute('examId');		

		$results = $this->reportRepository->instructorReportByExamAllStudents($examId);

		$results = $this->calculatePercentCorrect($results);		

		$code = 202;
		return array('students'=>$results,'status_code'=>$code);

	}


	public function reportDetailsOneStudent(Request $request)
	{
		
		$studentId = $request->getAttribute('studentId');
		$examId = $request->getAttribute('examId');		

		$results = $this->reportRepository->instructorReportOneStudent($studentId, $examId);

		$code = 202;
		return array('student'=>$results,'status_code'=>$code);
	}

	private function calculatePercentCorrect($rows)
	{
		$newRows = [];
		foreach($rows as $row){
			$percent =(int)$row['Correct'] / ((int)$row['Correct']+(int)$row['Wrong'])*(100);
			$row['Percent'] = $percent;
			array_push($newRows, $row);
		}
		return $newRows;

	}
}