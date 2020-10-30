<?php
declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use App\Application\Actions\User\UserCreateAction;
use App\Application\Actions\User\LoginUserAction;

//profile actions
use App\Application\Actions\Profile\WriteProfileAction;
use App\Application\Actions\Profile\UploadProfileImageAction;
use App\Application\Actions\Profile\ViewProfileAction;



//exam and its content related actions
use App\Application\Actions\Exam\ListExamsAction;
use App\Application\Actions\Exam\ExamWriteAction;
use App\Application\Actions\Exam\ViewExamAction;
use App\Application\Actions\Exam\SubjectWriteAction;
use App\Application\Actions\Exam\ListSubjectsAction;
use App\Application\Actions\Exam\QuestionWriteAction;
use App\Application\Actions\Exam\ChoiceWriteAction;
use App\Application\Actions\Exam\ViewQuestionAction;
use App\Application\Actions\Exam\AnswerWriteAction;
use App\Application\Actions\Exam\CloseExamAction;


//reports
use App\Application\Actions\Report\TeacherViewReportAllExams;
use App\Application\Actions\Report\TeacherViewAllStudentsByExam;
use App\Application\Actions\Report\TeacherViewDetailStudent;


//enroll via file upload
use App\Application\Actions\Enroll\EnrollStudentToSubject;

//delete actions
use App\Application\Actions\Exam\ChoiceDeleteAction;
use App\Application\Actions\Exam\QuestionDeleteAction;

//Middlewares
use App\Application\Middleware\SessionMiddleware;
use App\Application\Middleware\ValidationMiddleware;
use App\Application\Middleware\LevelGuardMiddleware;

//Slim classes
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;


return function (App $app) {
    $app->group('/api', function (Group $group) {

        /*
            Used by both levels
        */

        //root.
        $group->get('/',ViewUserAction::class)->setName('root');

        //user: login and registration
        $group->post('/login', LoginUserAction::class)->setName('login');
        $group->post('/register', UserCreateAction::class)
            ->add(ValidationMiddleware::class)->setName('register');
        $group->get('/me',ViewUserAction::class)->setName('me');
        

        //profile actions -> level1 and level0
        $group->post('/profile', WriteProfileAction::class)
            ->setName('write-profile');
        $group->post('/profile/image', UploadProfileImageAction::class)
            ->setName('upload-avatar');
        $group->get('/profile', ViewProfileAction::class)
            ->setName('view-profile');
       
        //Exam: get current exams 
        $group->get('/exams', ListExamsAction::class)
            ->setName('exams');

        $group->get('/exam/{examId}', ViewExamAction::class)
            ->setName('view-exam');            
            
        //answers by students
        $group->post('/answer', AnswerWriteAction::class)
            ->setName('write-answer'); 

        $group->post('/logout', function($req, $res){
            return $res->withStatus(401);           
        })->setName('logout');

        /*
            Only for level 1 i.e. teachers
        */
        $group->group('', function (Group $group) {

            //Subject: current subjects and update/insert subject
            $group->post('/subject', SubjectWriteAction::class)->setName('write-subject');
            $group->get('/subjects', ListSubjectsAction::class)->setName('subjects');

            $group->post('/exam', ExamWriteAction::class)->setName('write-exam');  

            $group->put('/exam/{examId}', CloseExamAction::class)->setName('close-exam');

            //Question: get questions and update/insert question
            $group->get('/question/{questionId}', ViewQuestionAction::class)
                ->setName('question');
            $group->post('/question', QuestionWriteAction::class)
                ->setName('write-question');
        
            //Choice: insert choices into question id
            $group->post('/question/{questionId}', ChoiceWriteAction::class)
                ->setName('write-choice');

            //for specific exam all students
            $group->get('/report/exams/{examId}', TeacherViewAllStudentsByExam::class)
                ->setName('report-exam');

            //for specific exam and specific student
            $group->get('/report/exams/{examId}/{studentId}', TeacherViewDetailStudent::class)
                ->setName('report-exam-student');

            $group->post('/enroll/{subjectId}', EnrollStudentToSubject::class)
                ->setName('enroll-students');   

            // delete actions 
            $group->delete('/choice/{choiceId}', ChoiceDeleteAction::class)
                ->setName('choice-delete'); 
            $group->delete('/question/{questionId}', QuestionDeleteAction::class)
                ->setName('question-delete');     
            
        })->add(LevelGuardMiddleware::class);    

        
        
    });
    
};
