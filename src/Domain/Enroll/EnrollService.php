<?php
declare(strict_types=1);

namespace App\Domain\Enroll;

use Psr\Http\Message\ServerRequestInterface as Request;


use App\Domain\Exam\ExamNotFoundException;
use App\Infrastructure\Persistence\Enroll\EnrollRepository;
use \UnexpectedValueException;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;



final class EnrollService 
{
	
	private $enrollRepository;
	

	public function __construct(EnrollRepository $enrollRepository)
	{
		$this->enrollRepository = $enrollRepository;
	}


	public function writeFromFile(Request $request)
	{
		
		$uploadedFile = $request->getUploadedFiles();
		$subjectId = $request->getAttribute('subjectId')??false;
				
		if(!$subjectId) {
			throw new \Exception("subject id not given", 1);
		}

		if(empty($uploadedFile['students'])) {
			throw new \Exception("No file uploaded", 1);		
		} 

				
		$stream = $uploadedFile['students']->getStream();
		$handle = $stream->detach();

		$result = [];
		while($data = fgetcsv($handle)){
			$this->validateEnrollment($data);
			$studentId = $data[0];

			$enroll = new Enroll(
				$studentId,
				$subjectId
			);

			try {
				$result[]=$this->enrollRepository->insert($enroll);
			} catch(\Exception $e) {
				continue;
			}
		//var_dump($enroll->jsonSerialize());

		}
		fclose($handle);

		$code = 200;
		return array('result'=>$result,'status_code'=>$code);
	

	}

	private function validateEnrollment($data)
	{
		//var_dump($data); die;
		$r = v::alnum()
			->length(8,10)
			->validate($data[0]??false);
		if(!$r) throw new \Exception(__METHOD__.": student Id must be 8-10 digits long ".$data[0], 1);
		
	}
		


	
}