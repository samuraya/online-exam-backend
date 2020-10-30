<?php
declare(strict_types=1);

namespace App\Domain\Report;

use Psr\Http\Message\ServerRequestInterface as Request;

// use App\Domain\Subject\Subject;
// use App\Domain\Question\Question;

// use App\Domain\Subject\SubjectNotFoundException;
use App\Infrastructure\Persistence\Report\ReportRepository;
// use \UnexpectedValueException;
// use Respect\Validation\Validator as v;
// use Respect\Validation\Exceptions\NestedValidationException;

//use App\Infrastructure\Persistence\Subject\TeacherReportRepository;



final class TeacherReportService 
{
	
	private $reportRepository;
	 

	public function __construct(ReportRepository $reportRepository)
	{
		$this->reportRepository = $reportRepository;
	}



	public function reportAllStudentsByExam(Request $request)
	{
 				
		//$data = $request->getParsedBody();
		$examId = $request->getAttribute('examId');
		//$userId = $_SESSION['user_id'];

		$results = $this->reportRepository->instructorReportByExamAllStudents($examId);

		$results = $this->calculatePercentCorrect($results);

		//var_dump($results);

		$code = 202;
		return array('students'=>$results,'status_code'=>$code);

	}


	public function reportDetailsOneStudent(Request $request)
	{


		//$data = $request->getParsedBody();
		$studentId = $request->getAttribute('studentId');
		$examId = $request->getAttribute('examId');
		//var_dump($studentId,$examId); die;

		$results = $this->reportRepository->instructorReportOneStudent($studentId, $examId);

		$code = 202;
		return array('student'=>$results,'status_code'=>$code);



	}

	private function calculatePercentCorrect($rows)
	{
		$newRows = [];
		foreach($rows as $row){
			$percent =(int)$row['Correct'] / ((int)$row['Correct']+(int)$row['Wrong']);
			$row['Percent'] = $percent;
			array_push($newRows, $row);
		}
		return $newRows;

	}
}