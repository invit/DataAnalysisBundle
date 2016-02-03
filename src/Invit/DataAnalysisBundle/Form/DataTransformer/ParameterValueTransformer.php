<?php

namespace Invit\DataAnalysisBundle\Form\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Invit\DataAnalysisBundle\Entity\DataAnalysisQueryParameter;
use Symfony\Component\Form\DataTransformerInterface;
use Invit\DataAnalysisBundle\Entity\DataAnalysisQuerySubscriptionParameterValue;

class ParameterValueTransformer implements DataTransformerInterface
{
    private $queryObject;
    private $em;

    public function __construct(EntityManagerInterface $em, $queryObject)
    {
        $this->em = $em;
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
            $value = $parameterValue->getValue();

            switch($parameterValue->getParameter()->getType()) {
                case DataAnalysisQueryParameter::ENTITY_TYPE:
                    $value = $this->em->getReference($parameterValue->getParameter()->getSelection(), $value);
                    break;
                case DataAnalysisQueryParameter::DATE_TYPE:
                    $value = new \DateTime($value);
                    break;
            }

            $data[$parameterValue->getParameter()->getName()] = $value;
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

            $value = $data[$parameter->getName()];

            switch($parameter->getType()) {
                case DataAnalysisQueryParameter::ENTITY_TYPE:
                    $value = $value->getId();
                    break;
                case DataAnalysisQueryParameter::DATE_TYPE:
                    $value = $value->format('Y-m-d');
                    break;
            }

            $parameterValue->setValue($value);
            $collection->add($parameterValue);
        }

        return $collection;
    }
}
