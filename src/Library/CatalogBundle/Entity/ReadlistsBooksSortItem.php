<?php
namespace Library\CatalogBundle\Entity;

class ReadlistsBooksSortItem
{
    /**
     * @var integer
     */
    private $id;
    /**
     * @var integer
     */
    private $position;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return ReadlistsBooksSortItem
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param int $position
     * @return ReadlistsBooksSortItem
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }



}