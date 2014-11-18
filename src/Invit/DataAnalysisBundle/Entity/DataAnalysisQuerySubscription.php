<?php

namespace Invit\DataAnalysisBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * DataAnalysisQuery
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Invit\DataAnalysisBundle\Entity\DataAnalysisQuerySubscriptionRepository")
 */
class DataAnalysisQuerySubscription {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var DataAnalysisQuery
     *
     * @ORM\ManyToOne(targetEntity="DataAnalysisQuery", inversedBy="subscriptions")
     * @ORM\JoinColumn(name="query_id", referencedColumnName="id")
     */
    private $query;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="DataAnalysisQuerySubscriptionParameterValue", mappedBy="subscription", cascade={"all"}, orphanRemoval=true)
     */
    private $parameterValues;

    /**
     * @var string
     *
     * @ORM\Column(name="resultHash", type="string", length=255, nullable=true)
     */
    private $resultHash;

    /**
     * @var string
     *
     * @ORM\Column(name="channel", type="string", length=255, nullable=false)
     */
    private $channel;

    /**
     * @var int
     *
     * @ORM\Column(name="minute", type="smallint", length=2, nullable=true)
     */
    private $minute;

    /**
     * @var int
     *
     * @ORM\Column(name="hour", type="smallint", length=2, nullable=true)
     */
    private $hour;

    /**
     * @var int
     *
     * @ORM\Column(name="day", type="smallint", length=1, nullable=true)
     */
    private $day;

    /**
     * @var int
     *
     * @ORM\Column(name="month", type="smallint", length=2, nullable=true)
     */
    private $month;

    public function __construct()
    {
        $this->parameterValues = new ArrayCollection();
    }

    public function __toString()
    {
        return (string)$this->getId();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return DataAnalysisQuery
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param DataAnalysisQuery $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * @return ArrayCollection
     */
    public function getParameterValues()
    {
        return $this->parameterValues;
    }

    public function getParameterValuesArray()
    {
        $parameterValuesArray = [];
        foreach($this->getParameterValues() as $parameterValue){
            $parameterValuesArray[$parameterValue->getParameter()->getName()] = $parameterValue->getValue();
        }
        return $parameterValuesArray;
    }

    /**
     * @param ArrayCollection $parameterValues
     */
    public function setParameterValues($parameterValues)
    {
        $this->parameterValues = $parameterValues;
    }

    /**
     * @param DataAnalysisQuerySubscriptionParameterValue $parameterValue
     */
    public function addParameterValue(DataAnalysisQuerySubscriptionParameterValue $parameterValue)
    {
        $parameterValue->setSubscription($this);
        $this->parameterValues->add($parameterValue);
    }

    /**
     * @param DataAnalysisQueryParameterValue $parameterValue
     */
    public function removeParameterValue(DataAnalysisQuerySubscriptionParameterValue $parameterValue)
    {
        $this->parameterValues->removeElement($parameterValue);
    }

    /**
     * @return string
     */
    public function getResultHash()
    {
        return $this->resultHash;
    }

    /**
     * @param string $resultHash
     */
    public function setResultHash($resultHash)
    {
        $this->resultHash = $resultHash;
    }

    /**
     * @return string
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param string $channel
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;
    }

    /**
     * @return int
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param int $day
     */
    public function setDay($day)
    {
        $this->day = $day;
    }

    /**
     * @return int
     */
    public function getMinute()
    {
        return $this->minute;
    }

    /**
     * @param int $minute
     */
    public function setMinute($minute)
    {
        $this->minute = $minute;
    }

    /**
     * @return int
     */
    public function getHour()
    {
        return $this->hour;
    }

    /**
     * @param int $hour
     */
    public function setHour($hour)
    {
        $this->hour = $hour;
    }

    /**
     * @return int
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param int $month
     */
    public function setMonth($month)
    {
        $this->month = $month;
    }
}