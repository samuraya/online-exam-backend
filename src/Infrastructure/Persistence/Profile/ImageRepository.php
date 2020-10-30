<?php
declare(strict_types=1);
namespace App\Infrastructure\Persistence\Profile;

use \PDO;
use App\Infrastructure\Persistence\Finder;

use App\Domain\Profile\ProfileNotFoundException;
use App\Infrastructure\Persistence\BaseRepository;

class ImageRepository
{
	private $imageDirectory;
	protected const UPLOAD_PATH = '/uploads/images';

	public function __construct($root_path)
 	{
 		$this->imageDirectory =$root_path.self::UPLOAD_PATH;
 		$this->publicDirectory = $_SERVER['SCRIPT_NAME'].self::UPLOAD_PATH; 		
 	}

 	public function upload($file)
	{
	    $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
	    $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
	    $filename = sprintf('%s.%0.8s', $basename, $extension);
	    //var_dump($file); die;
	    $file->moveTo($this->imageDirectory . DIRECTORY_SEPARATOR . $filename);

	    return $filename;
	}

	public function retrieveImage($filename)
	{
		$openFile = fopen($this->imageDirectory.DIRECTORY_SEPARATOR.$filename, 'rb');
        $stream = new \Slim\Psr7\Stream($openFile);
        return $stream;
	}

	public function retrieveLink($filename)
	{
		return $this->publicDirectory.DIRECTORY_SEPARATOR.$filename;
	}






}