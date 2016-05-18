<?php

namespace Invit\DataAnalysisBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;
use Invit\DataAnalysisBundle\DBAL\Types\QueryLanguageType;

/**
 * DataAnalysisQuery.
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class DataAnalysisQuery
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @DoctrineAssert\Enum(entity="Invit\DataAnalysisBundle\DBAL\Types\QueryLanguageType")
     * @ORM\Column(name="query_language", type="QueryLanguageType", nullable=false)
     */
    private $queryLanguage = QueryLanguageType::SQL;

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
     * @ORM\Column(name="icon", type="string", length=100, nullable=true)
     */
    private $icon;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
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
     * @ORM\OneToMany(targetEntity="DataAnalysisQueryParameter", mappedBy="query", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $parameters;

    /**
     * @ORM\OneToMany(targetEntity="DataAnalysisQuerySubscription", mappedBy="query", cascade={"all"}, orphanRemoval=true)
     */
    private $subscriptions;

    public function __construct()
    {
        $this->parameters = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getTitle();
    }

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
     * @return string
     */
    public function getQueryLanguage()
    {
        return $this->queryLanguage;
    }

    /**
     * @param string $queryLanguage
     */
    public function setQueryLanguage($queryLanguage)
    {
        $this->queryLanguage = $queryLanguage;
    }

    /**
     * Set query.
     *
     * @param string $query
     *
     * @return DataAnalysisQuery
     */
    public function setQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Get query.
     *
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return DataAnalysisCategory
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
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
     * @param ArrayCollection $parameters
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @return ArrayCollection
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

    /**
     * @return ArrayCollection
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

    /**
     * @param ArrayCollection $subscriptions
     */
    public function setSubscriptions($subscriptions)
    {
        $this->subscriptions = $subscriptions;
    }

    /**
     * @param DataAnalysisQuerySubscription $subscription
     */
    public function addSubscription(DataAnalysisQuerySubscription $subscription)
    {
        $subscription->setQuery($this);
        $this->subscriptions->add($subscription);
    }

    /**
     * @param DataAnalysisQuerySubscription $subscription
     */
    public function removeSubscription(DataAnalysisQuerySubscription $subscription)
    {
        $this->subscriptions->removeElement($subscription);
    }
}
