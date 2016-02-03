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
use Weekend4two\Bundle\BackendBundle\Form\Extension\Type\CodeType;

class DataAnalysisQueryAdmin extends Admin
{
    protected function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        if (in_array($action, ['list', 'create'])) {
            return;
        }

        $admin = $this->isChild() ? $this->getParent() : $this;

        $id = $admin->getRequest()->get('id');

        $menu->addChild('edit', ['uri' => $admin->generateUrl('edit', ['id' => $id]), 'label' => 'Bearbeiten']);
        $menu->addChild('executeQuery', ['uri' => $admin->generateUrl('executeQuery', ['id' => $id]), 'label' => 'Query ausführen']
        );

        $menu->addChild(
            'subscriptions',
            ['uri' => $admin->generateUrl('invit.admin.data_analysis_query_subscription.list', ['id' => $id]), 'label' => 'Abo']
        );

        foreach ($this->getModelManager()->getEntityManager('Invit\DataAnalysisBundle\Entity\DataAnalysisCategory')->getRepository('Invit\DataAnalysisBundle\Entity\DataAnalysisCategory')->findBy([], ['title' => 'ASC']) as $category) {
            $menu->addChild('category_'.$category->getId(), ['label' => $category->getTitle(), 'attributes' => ['dropdown' => 'true', 'class' => 'nav-header exp', 'data-category' => $category->getId()]]);

            foreach ($this->getModelManager()->getEntityManager('Invit\DataAnalysisBundle\Entity\DataAnalysisQuery')->getRepository('Invit\DataAnalysisBundle\Entity\DataAnalysisQuery')->findBy(['category' => $category], ['title' => 'ASC']) as $query) {
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
            ->add('category', null, ['label' => 'Kategorie'])
            ->add('title', null, ['label' => 'Titel'])
            ->add('description', null, ['label' => 'Beschreibung'])
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('category', null, ['label' => 'Kategorie'])
            ->add('title', null, ['label' => 'Titel'])
            ->add('description', null, ['label' => 'Beschreibung'])
            ->add('_action', 'actions', [
                'actions' => [
                    'actions' => ['template' => 'InvitDataAnalysisBundle:Admin:list__query_actions.html.twig'],
                ],
            ])
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('category', null, ['label' => 'Kategorie'])
            ->add('title', null, ['label' => 'Titel'])
            ->add('description', null, ['label' => 'Beschreibung'])
            ->add('parameters', 'sonata_type_collection', [
                'label' => 'Parameter',
                'by_reference' => false,
            ], [
                'edit' => 'inline',
                'inline' => 'table',
            ])
            ->add('query', CodeType::class, [
                'label' => 'Query',
                'code_mode' => 'sql',
                'required' => false,
            ])
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
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('executeQuery', $this->getRouterIdParameter().'/query/execute');
        $collection->add('exportQuery', $this->getRouterIdParameter().'/query/export/{format}');
        $collection->add('setQueryParameter', $this->getRouterIdParameter().'/query/set-parameter');

        $collection->remove('batch');
    }

    /**
     * @return array
     */
    public function getBatchActions()
    {
        return [];
    }
}
