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
use Itb\Model\Project;

/**
 * class for project
 * Class ProjectController
 * @package Itb\Controller
 */
class ProjectController
{
    /**
     * display present projects
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    public function projectsAction(Request $request, Application $app)
    {
        $projectsPresent = Project::searchByColumn('status', 'active');

        $argsArray = [
            'projectsPresent' => $projectsPresent
        ];

        $templateName = 'projects';
        return $app['twig']->render($templateName . '.html.twig', $argsArray);
    }

    /**
     * display create member page
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    public function memberProjectCreateAction(Request $request, Application $app)
    {
        $user = $app['session']->get('user');

        $argsArray = [
            'message' =>'please create project',
            'id' => $user['id'],
        ];

        $templateName = 'memberProjectCreate';
        return $app['twig']->render($templateName . '.html.twig', $argsArray);
    }

    /**
     * display edit project
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    public function memberProjectEditAction(Request $request, Application $app)
    {
        $user = $app['session']->get('user');
        $id = $user['id'];

        $columnName = 'supervisor';

        $project = Project::searchIdByColumn($columnName, $id);

        $argsArray = [
            'message' =>'please create project',
            'id' => $user['id'],
            'projects'=>$project,
            'edit'=>'edit'
        ];

        $templateName = 'memberProjectEdit';
        return $app['twig']->render($templateName . '.html.twig', $argsArray);
    }

    /**
     * displaying specific one project were picked
     * @param Request $request
     * @param Application $app
     * @param $id
     * @return mixed
     */
    public function memberEditProjectAction(Request $request, Application $app, $id)
    {
        $user = $app['session']->get('user');
        $rank = $user['rank'];
        $userId =$user['id'];

        $app['session']->set('user', array('id'=>$userId, 'rank'=>$rank, 'projectPickedId' =>$id));

        $projectRow = Project::getOneById($id);

        $argsArray = array(
            'id' => $user['id'],
            'projectRow' => $projectRow,
            'edit' => 'edit',
            'message' => 'Please fill new details'
        );

        $templateName = 'memberProjectEditWithInputFields';
        return $app['twig']->render($templateName . '.html.twig', $argsArray);
    }

    /**
     * changing project details
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    public function changeProjectDetailsAction(Request $request, Application $app)
    {
        $user = $app['session']->get('user');
        $projectId = $user['projectPickedId'];

        $paramsPost = $request->request->all();
        $name = $paramsPost['name'];
        $supervisor = $paramsPost['supervisor'];
        $description = $paramsPost['description'];
        $status = $paramsPost['status'];

        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $supervisor = filter_var($supervisor, FILTER_SANITIZE_STRING);
        $description = filter_var($description, FILTER_SANITIZE_STRING);
        $status = filter_var($status, FILTER_SANITIZE_STRING);

        $errors = array();
        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_ext = strtolower(end(explode('.', $_FILES['image']['name'])));

        $expensions = array('jpeg', 'jpg', 'png');

        if (in_array($file_ext, $expensions) === false) {
            $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
        }
        if ($file_size > 5000000) {
            $errors[] = 'File must be not bigger then 5 MB';
        }
        if (empty($errors) == true) {
            move_uploaded_file($file_tmp, "images/" . $file_name);
        }
        $projectRow = Project::getOneById($projectId);
        $projectRowImage = $projectRow->getImage();
        $image =  $projectRowImage;

        $project = new Project();
        $project->setId($projectId);
        $project->setName($name);
        $project->setSupervisor($supervisor);
        $project->setDescription($description);
        $project->setStatus($status);

        if ($file_name) {
            $image = $file_name;
        }
        $project->setImage($image);
        $updateSuccess = Project::update($project);
        $projectRow = Project::getOneById($projectId);

        $argsArray = array(
            'id' => $user['id'],
            'projectRow' => $projectRow,
            'edit' => 'edit',
            'message' => 'Thanks Your Details Has Been Changed'
        );

        $templateName = 'memberProjectEditWithInputFields';
        return $app['twig']->render($templateName . '.html.twig', $argsArray);
    }

    /**
     * display table for deleting projects
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    public function memberProjectDeleteAction(Request $request, Application $app)
    {
        $user = $app['session']->get('user');
        $id = $user['id'];

        $columnName = 'supervisor';

        $project = Project::searchIdByColumn($columnName, $id);

        $argsArray = [
            'message' =>'',
            'id' => $user['id'],
            'projects'=>$project,
            'delete'=>'delete'
        ];

        $templateName = 'memberProjectDelete';
        return $app['twig']->render($templateName . '.html.twig', $argsArray);
    }

    /**
     * deleting project
     * @param Request $request
     * @param Application $app
     * @param $id
     * @return mixed
     */
    public function memberDeleteProjectAction(Request $request, Application $app, $id)
    {
        $user = $app['session']->get('user');
        $rank = $user['rank'];
        $userId =$user['id'];

        $app['session']->set('user', array('id'=>$userId, 'rank'=>$rank, 'projectPickedId' =>$id));

        $deletedSuccessfully = Project::delete($id);

        $user = $app['session']->get('user');
        $id = $user['id'];

        $columnName = 'supervisor';

        $project = Project::searchIdByColumn($columnName, $id);

        $argsArray = [
            'message' =>'Project was deleted successfully',
            'id' => $user['id'],
            'projects'=>$project,
            'delete'=>'delete'
        ];

        $templateName = 'memberProjectDelete';
        return $app['twig']->render($templateName . '.html.twig', $argsArray);
    }

    /**
     * redirecting to page for project creating by admin
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    public function adminProjectCreateAction(Request $request, Application $app)
    {
        $user = $app['session']->get('user');
        $localId = $user['id'];

        $argsArray = array(
            'message'=> 'please create project'
        );

        $templateName = 'adminProjectCreate';

        return $app['twig']->render($templateName . '.html.twig', $argsArray);
    }

    /**
     *project creating
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    public function createProjectAdminAction(Request $request, Application $app)
    {
        $user = $app['session']->get('user');
        $localId = $user['id'];

        $paramsPost = $request->request->all();
        $name = $paramsPost['name'];
        $supervisor = $paramsPost['supervisor'];
        $description = $paramsPost['description'];
        $status = $paramsPost['status'];

        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $supervisor = filter_var($supervisor, FILTER_SANITIZE_STRING);
        $description = filter_var($description, FILTER_SANITIZE_STRING);
        $status = filter_var($status, FILTER_SANITIZE_STRING);

        $errors = array();
        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_ext = strtolower(end(explode('.', $_FILES['image']['name'])));

        $expensions = array('jpeg', 'jpg', 'png');

        if (in_array($file_ext, $expensions) === false) {
            $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
        }
        if ($file_size > 5000000) {
            $errors[] = 'File must be not bigger then 5 MB';
        }
        if (empty($errors) == true) {
            move_uploaded_file($file_tmp, "images/" . $file_name);
        }

        $image =  'default.jpg';

        $project = new Project();

        $project->setName($name);
        $project->setSupervisor($supervisor);
        $project->setDescription($description);
        $project->setStatus($status);

        if ($file_name) {
            $image = $file_name;
        }
        $project->setImage($image);

        $updateSuccess = Project::insert($project);

        $argsArray = array(
            'message' => 'The project has been created successfully'
        );

        $templateName = 'adminProjectCreate';

        return $app['twig']->render($templateName . '.html.twig', $argsArray);
    }

    /**
     * display project edit list
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    public function adminProjectEditAction(Request $request, Application $app)
    {
        $user = $app['session']->get('user');
        $id = $user['id'];

        $projects = Project::getAll();

        $argsArray = [
            'message' =>'please create project',
            'id' => $user['id'],
            'projects'=>$projects,
            'edit'=>'edit'
        ];

        $templateName = 'adminProjectEdit';
        return $app['twig']->render($templateName . '.html.twig', $argsArray);
    }

    /**
     * displaying specific project for editing it
     * @param Request $request
     * @param Application $app
     * @param $id
     * @return mixed
     */
    public function adminEditProjectAction(Request $request, Application $app, $id)
    {
        $user = $app['session']->get('user');
        $rank = $user['rank'];
        $userId =$user['id'];

        $app['session']->set('user', array('id'=>$userId, 'rank'=>$rank, 'projectPickedId' =>$id));

        $projectRow = Project::getOneById($id);

        $argsArray = array(
            'id' => $user['id'],
            'projectRow' => $projectRow,
            'edit' => 'edit',
            'message' => 'Please fill new details'
        );

        $templateName = 'adminProjectEditWithInputFields';
        return $app['twig']->render($templateName . '.html.twig', $argsArray);
    }

    /**
     * changing project details based on information provided in text fields
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    public function changeProjectDetailsAdminAction(Request $request, Application $app)
    {
        $user = $app['session']->get('user');
        $projectId = $user['projectPickedId'];

        $paramsPost = $request->request->all();
        $name = $paramsPost['name'];
        $supervisor = $paramsPost['supervisor'];
        $description = $paramsPost['description'];
        $status = $paramsPost['status'];

        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $supervisor = filter_var($supervisor, FILTER_SANITIZE_STRING);
        $description = filter_var($description, FILTER_SANITIZE_STRING);
        $status = filter_var($status, FILTER_SANITIZE_STRING);

        $errors = array();
        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_ext = strtolower(end(explode('.', $_FILES['image']['name'])));

        $expensions = array('jpeg', 'jpg', 'png');

        if (in_array($file_ext, $expensions) === false) {
            $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
        }
        if ($file_size > 5000000) {
            $errors[] = 'File must be not bigger then 5 MB';
        }
        if (empty($errors) == true) {
            move_uploaded_file($file_tmp, "images/" . $file_name);
        }
        $projectRow = Project::getOneById($projectId);
        $projectRowImage = $projectRow->getImage();

        $image =  $projectRowImage;

        $project = new Project();
        $project->setId($projectId);
        $project->setName($name);
        $project->setSupervisor($supervisor);
        $project->setDescription($description);
        $project->setStatus($status);

        if ($file_name) {
            $image = $file_name;
        }
        $project->setImage($image);

        $updateSuccess = Project::update($project);

        $projectRow = Project::getOneById($projectId);

        $argsArray = array(
            'id' => $user['id'],
            'projectRow' => $projectRow,
            'edit' => 'edit',
            'message' => 'Thanks Your Details Has Been Changed'
        );

        $templateName = 'adminProjectEditWithInputFields';
        return $app['twig']->render($templateName . '.html.twig', $argsArray);
    }

    /**
     * display project delete table
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    public function adminProjectDeleteAction(Request $request, Application $app)
    {
        $user = $app['session']->get('user');
        $id = $user['id'];

        $projects = Project::getAll();

        $argsArray = [
            'message' =>'',
            'id' => $user['id'],
            'projects'=>$projects,
            'delete'=>'delete'
        ];

        $templateName = 'adminProjectDelete';
        return $app['twig']->render($templateName . '.html.twig', $argsArray);
    }

    /**
     * deleting specific project
     * @param Request $request
     * @param Application $app
     * @param $id
     * @return mixed
     */
    public function adminDeleteProjectAction(Request $request, Application $app, $id)
    {
        $deletedSuccessfully = Project::delete($id);

        $user = $app['session']->get('user');

        $projects = Project::getAll();

        $argsArray = [
            'message' =>'Project was deleted successfully',
            'id' => $user['id'],
            'projects'=>$projects,
            'delete'=>'delete'
        ];

        $templateName = 'adminProjectDelete';
        return $app['twig']->render($templateName . '.html.twig', $argsArray);
    }
}
