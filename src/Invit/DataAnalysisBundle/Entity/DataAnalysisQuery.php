<?php

namespace Invit\DataAnalysisBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * DataAnalysisQuery
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class DataAnalysisQuery
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="query", type="text")
     */
    private $query;
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var DataAnalysisCategory
     *
     * @ORM\ManyToOne(targetEntity="DataAnalysisCategory")
     * @ORM\JoinColumn(name="dataAnalysisCategory_id", referencedColumnName="id")
     */
    private $category;

    /**
     *
     * @ORM\OneToMany(targetEntity="DataAnalysisQueryParameter", mappedBy="query", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $parameters;

    public function __construct()
    {
        $this->parameters = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set query
     *
     * @param string $query
     * @return DataAnalysisQuery
     */
    public function setQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Get query
     *
     * @return string 
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return DataAnalysisCategory
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $parameters
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @return mixed
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param DataAnalysisQueryParameter $parameter
     */
    public function addParameter(DataAnalysisQueryParameter $parameter)
    {
        $parameter->setQuery($this);
        $this->parameters->add($parameter);
    }

    /**
     * @param DataAnalysisQueryParameter $parameter
     */
    public function removeParameter(DataAnalysisQueryParameter $parameter)
    {
        $this->parameters->removeElement($parameter);
    }
}
