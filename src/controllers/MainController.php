<?php
/**
 * Created by PhpStorm.
 * User: den
 * Date: 23/03/2016
 * Time: 15:39
 */

namespace Itb\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Itb\Model\Member;
use Itb\Model\Student;
use Itb\Model\Project;

/**
 * main class
 * Class MainController
 * @package Itb\Controller
 */
class MainController
{
    /**
     * checking rank from the session
     * @param Request $request
     * @param Application $app
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
        public function homeAction(Request $request, Application $app)
        {
            $argsArray = [];

            if (null !== $app['session']->get('user')) {
                $user = $app['session']->get('user');
                $rank = $user['rank'];

                if ($rank === "admin") {
                    return $app->redirect('/loginSuccessAdmin');
                }
                if ($rank === "supervisor") {
                    return $app->redirect('/loginSuccessSupervisor');
                }
                if ($rank === "student") {
                    return $app->redirect('/loginSuccessStudent');
                }
            }

            $templateName = 'home';
            return $app['twig']->render($templateName . '.html.twig', $argsArray);
        }

    /**
     * display detailed member information
     * @param Request $request
     * @param Application $app
     * @param $id
     * @return mixed
     */
    public function detailMemAction(Request $request, Application $app, $id)
    {
        $studentsDetails = '';
        $textId = '';
        $textName = '';
        $projectName = '';

        $member = Member::getOneById($id);
        if ($member == null) {
            return $app->redirect('/wrongId');
        }

        $projectId = $member->getProjectId();
        $studentRows = Student::searchIdByColumn('projectId', $projectId);
        $projectRow = Project::getOneById($projectId);

        if ($projectRow) {
            $projectName = $projectRow->getName();
        }

        if ($studentRows) {
            $studentsDetails = 'Supervised student details';
            $textId = 'Student id is :';
            $textName='Student name is :';
        }
        $argsArray = [
            'member' => $member,
            'id' => $id,
            'students' => $studentRows,
            'studentsDetails' => $studentsDetails,
            'textId' => $textId,
            'textName' => $textName,
            'projectName'=> $projectName,
        ];

        $templateName = 'detailMem';
        return $app['twig']->render($templateName . '.html.twig', $argsArray);
    }

    /**
     *display detailed student
     * @param Request $request
     * @param Application $app
     * @param $id
     * @return mixed
     */
    public function detailStudAction(Request $request, Application $app, $id)
    {
        $student = Student::getOneById($id);

        if ($student == null) {
            return $app->redirect('/wrongId');
        }

        $projectId = $student->getProjectId();
        $memberId = $student->getMemberId();
        $projectRow = Project::getOneById($projectId);
        $projectName = $projectRow->getName();
        $memberRow = Member::getOneById($memberId);
        $memberName = $memberRow->getName();

        $argsArray = [
            'student' => $student,
            'id' => $id,
            'projectName' =>  $projectName,
            'memberName' => $memberName,
        ];

        $templateName = 'detailStud';
        return $app['twig']->render($templateName . '.html.twig', $argsArray);
    }

    /**
     * display detailed the project
     * @param Request $request
     * @param Application $app
     * @param $id
     * @return mixed
     */
    public function detailProjectAction(Request $request, Application $app, $id)
    {
        $project = Project::getOneById($id);

        if ($project == null) {
            return $app->redirect('/wrongId');
        }

        $supervisorId = $project->getSupervisor();
        $supervisorRow = Member::getOneById($supervisorId);
        $supervisorName ='';
        if ($supervisorRow) {
            $supervisorName = $supervisorRow->getName();
        }
        $studentRows = Student::searchIdByColumn('projectId', $id);
        $studentsDetails = '';
        $textId = '';
        $textName= '';

        if ($studentRows) {
            $studentsDetails = 'Students assigned to project ';
            $textId = 'Student id is :';
            $textName='Student name is :';
        }

        $argsArray = [
            'project' => $project,
            'id' => $id,
            'supervisorName' => $supervisorName,
            'studentsDetails' => $studentsDetails,
            'textId'=> $textId,
            'textName'=> $textName,
            'studentRow' => $studentRows
        ];

        $templateName = 'detailProject';
        return $app['twig']->render($templateName . '.html.twig', $argsArray);
    }

    /**
     * display the error if something goes wrong
     * @param Application $app
     * @param $message
     * @return mixed
     */
    public static function error404(Application $app, $message)
    {
        $argsArray = [
            'name' => 'Fabien',
        ];
        $templateName = '404';
        return $app['twig']->render($templateName . '.html.twig', $argsArray);
    }

    /**
     * display past work members, students, projects
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    public function pastWorkAction(Request $request, Application $app)
    {
        $membersPast = Member::searchByColumn('status', 'past');
        $studentsPast = Student::searchByColumn('status', 'past');
        $projectsPast = Project::searchByColumn('status', 'past');

        $argsArray = [
            'table1' => 'Supervisors',
            'table2' => 'Students',
            'table3' => 'Projects',
            'membersPast' => $membersPast,
            'studentsPast' => $studentsPast,
            'projectsPast' =>  $projectsPast,
        ];

        $templateName = 'membersPast';
        return $app['twig']->render($templateName . '.html.twig', $argsArray);
    }

    /**
     * no id were mentioned
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    public function noEnteredIdAction(Request $request, Application $app)
    {
        $argsArray = [

        ];

        $templateName = 'noIdEntered';
        return $app['twig']->render($templateName . '.html.twig', $argsArray);
    }

    /**
     * wrong id
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    public function wrongIdAction(Request $request, Application $app)
    {
        $argsArray = [

        ];

        $templateName = 'wrongId';
        return $app['twig']->render($templateName . '.html.twig', $argsArray);
    }
}
