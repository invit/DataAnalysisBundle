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
            ->add('title', null, ['label' => 'Titel'])
            ->add('name', null, ['label' => 'Parameter'])
            ->add('selection', null, ['label' => 'Auswahl', 'required' => false])
        ;
    }

    /**
     * @return array
     */
    public function getBatchActions()
    {
        return [];
    }
}
