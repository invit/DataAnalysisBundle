<?php

namespace Invit\DataAnalysisBundle\Entity;

/**
 * Interface DataAnalysisQueryParameterValueInterface
 * @package Invit\DataAnalysisBundle\Entity
 */
interface DataAnalysisQueryParameterValueInterface
{
    /**
     * Get id.
     *
     * @return int
     */
    public function getId();

    /**
     * @return DataAnalysisQueryParameter
     */
    public function getParameter();

    /**
     * @param DataAnalysisQueryParameter $parameter
     */
    public function setParameter($parameter);

    /**
     * @return string
     */
    public function getValue();

    /**
     * @param string $value
     */
    public function setValue($value);
}
