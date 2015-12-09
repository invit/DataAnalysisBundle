<?php

namespace Invit\DataAnalysisBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DataAnalysisQuery.
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class DataAnalysisQuerySubscriptionParameterValue
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
     * @var DataAnalysisQueryParameter
     *
     * @ORM\ManyToOne(targetEntity="DataAnalysisQueryParameter")
     * @ORM\JoinColumn(name="parameter_id", referencedColumnName="id")
     */
    private $parameter;

    /**
     * @var DataAnalysisQuerySubscription
     *
     * @ORM\ManyToOne(targetEntity="DataAnalysisQuerySubscription", inversedBy="parameterValues")
     * @ORM\JoinColumn(name="subscription_id", referencedColumnName="id")
     */
    private $subscription;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;

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
     * @return DataAnalysisQueryParameter
     */
    public function getParameter()
    {
        return $this->parameter;
    }

    /**
     * @param DataAnalysisQueryParameter $parameter
     */
    public function setParameter($parameter)
    {
        $this->parameter = $parameter;
    }

    /**
     * @return DataAnalysisQuerySubscription
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

    /**
     * @param DataAnalysisQuerySubscription $subscription
     */
    public function setSubscription($subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}
