<?php
declare(strict_types=1);

namespace App\Application\Actions\Report;

use App\Application\Actions\Action;
//use App\Domain\Report\TeacherReportRepository;
use App\Domain\Report\TeacherReportService;
use Psr\Log\LoggerInterface;

abstract class TeacherReportAction extends Action
{
    /**
     * @var UserRepository
     */
    protected $teacherReportService;

    /**
     * @param LoggerInterface $logger
     * @param UserRepository  $userRepository
     */
    public function __construct(LoggerInterface $logger, TeacherReportService $teacherReportService)
    {
        parent::__construct($logger);
        $this->teacherReportService = $teacherReportService;
    }
}