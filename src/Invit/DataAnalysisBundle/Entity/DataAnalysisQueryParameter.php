<?php

namespace Invit\DataAnalysisBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DataAnalysisQuery.
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class DataAnalysisQueryParameter
{
    const TEXT_TYPE = 'text';
    const DATE_TYPE = 'date';
    const ENTITY_TYPE = 'entity';
    const CHOICE_TYPE = 'choice';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var DataAnalysisQuery
     *
     * @ORM\ManyToOne(targetEntity="DataAnalysisQuery", inversedBy="parameters")
     * @ORM\JoinColumn(name="dataAnalysisQuery_id", referencedColumnName="id")
     */
    private $query;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=60, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="selection", type="text", nullable=true)
     */
    private $selection;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $selection
     */
    public function setSelection($selection)
    {
        $this->selection = $selection;
    }

    /**
     * @return string
     */
    public function getSelection()
    {
        return $this->selection;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \Invit\DataAnalysisBundle\Entity\DataAnalysisQuery $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * @return \Invit\DataAnalysisBundle\Entity\DataAnalysisQuery
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return string
     */
    public function getType()
    {
        if (null === $this->getSelection()) {
            return self::TEXT_TYPE;
        } elseif (preg_match('/^date$/', $this->getSelection())) {
            return self::DATE_TYPE;
        } elseif (preg_match('/^\{(.*)\}$/', $this->getSelection())) {
            return self::CHOICE_TYPE;
        } elseif (preg_match("/^[a-zA-Z0-9]+\\\.*\\\[a-zA-Z0-9]+$/", $this->getSelection())) {
            return self::ENTITY_TYPE;
        }
    }
}
