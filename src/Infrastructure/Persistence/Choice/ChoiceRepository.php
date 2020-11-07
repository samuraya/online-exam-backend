<?php
declare(strict_types=1);
namespace App\Infrastructure\Persistence\Choice;

use \PDO;
use App\Infrastructure\Persistence\Finder;

use App\Domain\Question\QuestionNotFoundException;
use App\Infrastructure\Persistence\BaseRepository;


class ChoiceRepository extends BaseRepository
{
  protected $connection;

  public function __construct(PDO $connection)
  {
    $this->table = 'choice';
    parent::__construct($connection);
  }
  public function findByQuestion($questionId)
    {
       $sql = Finder::select($this->table)
      ->where('question_id = ?')
      ->getSql();

    Finder::resetAll();

    $stmt = $this->connection->prepare($sql);
    $stmt->execute([$questionId]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($rows==false || empty($rows)){
      return array();
    }   
        
        return $rows;
    }
}