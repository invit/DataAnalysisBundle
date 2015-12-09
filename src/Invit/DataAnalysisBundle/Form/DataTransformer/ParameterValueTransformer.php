<?php

namespace Invit\DataAnalysisBundle\Form\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;
use Invit\DataAnalysisBundle\Entity\DataAnalysisQuerySubscriptionParameterValue;

class ParameterValueTransformer implements DataTransformerInterface
{
    private $queryObject;

    public function __construct($queryObject)
    {
        $this->queryObject = $queryObject;
    }

    /**
     * Transforms an ArrayCollection of DataAnalysisQuerySubscriptionParameterValue into a key, value array.
     *
     * @param ArrayCollection|null $collection
     *
     * @return array
     */
    public function transform($collection)
    {
        if (null === $collection) {
            return;
        }

        $data = [];
        foreach ($collection as $parameterValue) {
            $data[$parameterValue->getParameter()->getName()] = $parameterValue->getValue();
        }

        return $data;
    }

    /**
     * Transforms key, value array into an ArrayCollection of DataAnalysisQuerySubscriptionParameterValue.
     *
     * @param array $data
     *
     * @return ArrayCollection|null
     */
    public function reverseTransform($data)
    {
        $collection = new ArrayCollection();
        foreach ($this->queryObject->getParameters() as $parameter) {
            $parameterValue = new DataAnalysisQuerySubscriptionParameterValue();
            $parameterValue->setParameter($parameter);
            $parameterValue->setValue($data[$parameter->getName()]);
            $collection->add($parameterValue);
        }

        return $collection;
    }
}
