<?php

namespace Invit\DataAnalysisBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Admin\AdminInterface;

use Knp\Menu\ItemInterface as MenuItemInterface;

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
    public function getBatchActions(){
        return array();
    }
}
