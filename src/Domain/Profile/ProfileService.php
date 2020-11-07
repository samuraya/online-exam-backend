<?php
declare(strict_types=1);

namespace App\Domain\Profile;

use Psr\Http\Message\ServerRequestInterface as Request;

use App\Domain\Profile\Profile;
use App\Domain\Profile\ProfileNotFoundException;
use App\Infrastructure\Persistence\Profile\ProfileRepository;
use App\Infrastructure\Persistence\Profile\ImageRepository;
use \UnexpectedValueException;
use Slim\Exception\HttpBadRequestException;
use App\Domain\DomainException\DomainUnauthorizedException;
use App\Domain\DomainException\DomainRecordNotFoundException;
use App\Domain\DomainException\DomainBadRequestException;


final class ProfileService 
{

	private const DEFAULT_LEVEL = 0;
	
	private $repository;

	public function __construct(ProfileRepository $profileRepository, ImageRepository $imageRepository)
	{
		$this->profileRepository = $profileRepository;
		$this->imageRepository = $imageRepository;
	}


	public function uploadImageFile(Request $request)
	{
				
		$uploadedFile = $request->getUploadedFiles();
		$userId = $_SESSION['user_id'];

		$userId = $_SESSION['user_id'] ?? false;

		if($userId===false) {
			throw new DomainBadRequestException("user unauthorized");	
		}
    	 // handle single input with single file upload
	    $uploadedFile = $uploadedFile['avatar']??FALSE;
	    if($uploadedFile===FALSE){
	    	throw new DomainBadRequestException("it should be avatar image");
	    }
	    if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
	        $filename = $this->imageRepository->upload($uploadedFile);

	        $obj = new class{
				function getId(){
					return $this->id;
				}
				function getImage(){
					return $this->image;
				}
				function jsonSerialize(){
					return array(
						'id'=>$this->getId(),
						'image'=>$this->getImage()
					);
				}

			};

			$obj->id = $userId;
			$obj->image = $filename;
			$this->profileRepository->update($obj);			
			return array('filename'=>$filename,'status_code'=>200);
	    }

	    throw new DomainBadRequestException("error while uploading image");

	}

	public function writeToProfile(Request $request):array
	{

		$data = $request->getParsedBody();

		$userId = $_SESSION['user_id'] ?? false;

		if($userId===false) {
			throw new DomainBadRequestException("user unauthorized");	
		}

		$firstName = $data['first_name'];
		$lastName = $data['last_name'];
		$email = $data['email'];

		$profile = new Profile(
					$userId,
					$firstName,
					$lastName,
					$email					
				);
		$profile = $this->profileRepository->save($profile);

		return array('profile'=>$profile,'status_code'=>200);		

	}

	public function retrieveProfile() {
		 
		$userId = $_SESSION['user_id'] ?? FALSE;
		
		if($userId===false) {
			throw new DomainBadRequestException("user unauthorized");
		}
		
		$profile = $this->profileRepository->findProfileById($userId);
		$profile = $profile[0] ?? false;

		if($profile===false) {
			throw new DomainRecordNotFoundException("Profile not found");
		}		
							
		$body = [
			'user_id'=>$profile['id'],
			'first_name'=>$profile['first_name'],
			'last_name'=>$profile['last_name'],
			'email'=>$profile['email'],
			'avatar'=>$profile['image']

		];
		$code = 202;

		return array('profile'=>$body,'status_code'=>$code);	
		
	}
}