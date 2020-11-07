<?php
declare(strict_types=1);
namespace App\Infrastructure\Persistence\Answer;

use \PDO;
use App\Infrastructure\Persistence\Finder;

use App\Domain\Answer\AnswerNotFoundException;
use App\Infrastructure\Persistence\BaseRepository;
use App\Domain\DomainException\DomainRecordNotFoundException;


class AnswerRepository extends BaseRepository
{
  protected $connection;

  public function __construct(PDO $connection)
  {
    $this->table = 'answer';
    parent::__construct($connection);
  }

  public function findByQuestion($questionId)
    {
      $sql = Finder::select($this->table)
      ->where('question_id = ?')
      ->getSql();

      $stmt = $this->connection->prepare($sql);
      $stmt->execute([$questionId]);
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if($rows==false || empty($rows)){
        $rows = [];
      }   
      return $rows;
    }
}