<?php

namespace Invit\DataAnalysisBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;

class DataAnalysisQueryParameterAdmin extends Admin
{
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', null, array('label' => 'Titel'))
            ->add('name', null, array('label' => 'Parameter'))
            ->add('selection', null, array('label' => 'Auswahl', 'required' => false))
        ;
    }

    /**
     * @return array
     */
    public function getBatchActions()
    {
        return array();
    }
}
