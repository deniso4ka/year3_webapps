<?php
/**
 * Created by PhpStorm.
 * User: den
 * Date: 23/03/2016
 * Time: 15:09
 */

namespace Itb\Model;

use Mattsmithdev\PdoCrud\DatabaseTable;

/**
 * member
 * Class Member
 * @package Itb\model
 */
class Member extends DatabaseTable
{
    /**
     *variable to hold am id
     * @var int
     */
    private $id;
    /**
     * variable to hold name
     * @var string
     */
    private $name;
    /**
     * variable used to hold projectId
     * @var int
     */
    private $projectId;
    /**
     * variable used to hold status
     * @var string
     */
    private $status;
    /**
     * variable used to hold image
     * @var string
     */
    private $image;



    /**
     * function used to get id
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * function used to set id
     * @param int
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * function used to get name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * function used to set name
     * @param string
     */
    public function setName($name)
    {
        $this->name = $name;
    }



    /**
     * function used to get projectId
     * @return int
     */
    public function getProjectId()
    {
        return $this->projectId;
    }



    /**
     * function used to set projectId
     * @param int
     */
    public function setProjectId($projectId)
    {
        $this->projectId = $projectId;
    }

    /**
     * function used to get status
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * function used to set status
     * @param tring
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * function used to get image
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * function used to set image
     * @param string
     */
    public function setImage($image)
    {
        $this->image = $image;
    }


    public static function searchIdByColumn($columnName, $id)
    {
        $db = new DatabaseManager();
        $connection = $db->getDbh();

        // wrap wildcard '%' around the serach text for the SQL query


        $statement = $connection->prepare('SELECT * from ' . static::getTableName()  . ' WHERE ' . $columnName . ' =:id');
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);
        $statement->setFetchMode(\PDO::FETCH_CLASS, __CLASS__);
        $statement->execute();

        $objects = $statement->fetchAll();

        return $objects;
    }
}
