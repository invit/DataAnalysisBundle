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

class DataAnalysisQueryAdmin extends Admin
{
    protected function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null){
        if (in_array($action, array('list', 'create'))) {
            return;
        }

        $admin = $this->isChild() ? $this->getParent() : $this;

        $id = $admin->getRequest()->get('id');

        $menu->addChild('edit', array('uri' => $admin->generateUrl('edit', array('id' => $id)), 'label' => 'Bearbeiten'));
        $menu->addChild('executeQuery', array('uri' => $admin->generateUrl('executeQuery', array('id' => $id)), 'label' => 'Query ausführen'));

        $menu->addChild(
            'subscriptions',
            array('uri' => $admin->generateUrl('invit.admin.data_analysis_query_subscription.list', array('id' => $id)), 'label' => 'Abo')
        );

        foreach($this->getModelManager()->getEntityManager('Invit\DataAnalysisBundle\Entity\DataAnalysisCategory')->getRepository('Invit\DataAnalysisBundle\Entity\DataAnalysisCategory')->findBy([], ['title' => 'ASC']) as $category){
            $menu->addChild('category_'.$category->getId(), ['label' => $category->getTitle(), 'attributes' => ['dropdown' => 'true', 'class' => 'nav-header exp', 'data-category' => $category->getId()]]);

            foreach($this->getModelManager()->getEntityManager('Invit\DataAnalysisBundle\Entity\DataAnalysisQuery')->getRepository('Invit\DataAnalysisBundle\Entity\DataAnalysisQuery')->findBy(['category' => $category], ['title' => 'ASC']) as $query){
                $menu['category_'.$category->getId()]->addChild('executeQuery'.$query->getId().$query->getId(), ['attributes' => ['class' => 'exp-hidden exp-menu cat-'.$category->getId()], 'uri' => $admin->generateUrl('executeQuery', ['id' => $query->getId()]), 'label' => $query->getTitle()]);
            }
        }
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('category', null, array('label' => 'Kategorie'))
            ->add('title', null, array('label' => 'Titel'))
            ->add('description', null, array('label' => 'Beschreibung'))
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('category', null, array('label' => 'Kategorie'))
            ->add('title', null, array('label' => 'Titel'))
            ->add('description', null, array('label' => 'Beschreibung'))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'actions' => array('template' => 'InvitDataAnalysisBundle:Admin:list__query_actions.html.twig')
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('category', 'sonata_type_model', array('label' => 'Kategorie'))
            ->add('title', null, array('label' => 'Titel'))
            ->add('description', null, array('label' => 'Beschreibung'))
            ->add('parameters', 'sonata_type_collection', array(
                'label' => 'Parameter',
                'by_reference' => false
            ), array(
                'edit' => 'inline',
                'inline' => 'table'
            ))
            ->add('query', null, array('label' => 'Query'))
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('query')
            ->add('title')
        ;
    }

    /**
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection) {
        $collection->add('executeQuery', $this->getRouterIdParameter().'/query/execute');
        $collection->add('exportQuery', $this->getRouterIdParameter().'/query/export/{format}');
        $collection->add('setQueryParameter', $this->getRouterIdParameter().'/query/set-parameter');

        $collection->remove('batch');
    }

    /**
     * @return array
     */
    public function getBatchActions(){
        return array();
    }
}
