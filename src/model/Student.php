<?php
/**
 * Created by PhpStorm.
 * User: den
 * Date: 29/03/2016
 * Time: 16:53
 */

namespace Itb\Model;

/**
 * student
 * Class Student
 * @package Itb\Model
 */
class Student extends MyDatabaseTable
{
    /**
     * variable is used to hold id
     * @var id
     */
    private $id;
    /**
     * variable is used to hold name
     * @var name
     */
    private $name;
    /**
     * variable is used to hold projectId
     * @var projectId
     */
    private $projectId;
    /**
     * variable is used to hold memberId
     * @var string
     */
    private $memberId;
    /**
     * variable is used to hold status
     * @var string
     */
    private $status;
    /**
     * variable is used to hold an image
     * @var string
     */
    private $image;


    /**
     * function is used to get id
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * function is used to set id
     * @param int
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * function is used to get Name
     * @return stringg
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * function is used to set name
     * @param string
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * function is used to get projectId
     * @return int
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * function is used to set project id
     * @param int
     */
    public function setProjectId($projectId)
    {
        $this->projectId = $projectId;
    }

    /**
     * function is used to get member id
     * @return int
     */
    public function getMemberId()
    {
        return $this->memberId;
    }

    /**
     * function is used to set member id
     * @param int
     */
    public function setMemberId($memberId)
    {
        $this->memberId = $memberId;
    }

    /**
     * function is used to get Image
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * function is used to set Image
     * @param string
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * function is used to set status
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * function is used
     * @param string
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
}
