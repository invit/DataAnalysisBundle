<?php

namespace Invit\DataAnalysisBundle\Admin;

use Invit\CoreBundle\Form\IconType;
use Invit\DataAnalysisBundle\Entity\DataAnalysisCategory;
use Invit\DataAnalysisBundle\Entity\DataAnalysisQuery;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Admin\AdminInterface;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Weekend4two\BackendBundle\Form\Extension\Type\CodeType;

class DataAnalysisQueryAdmin extends Admin
{
    protected $maxPerPage = 300;
    protected $datagridValues = [
        '_page' => 1,
        '_sort_order' => 'ASC', // sort direction
        '_sort_by' => 'category.id', // field name
    ];
    protected $listModes = [];

    public function createQuery($context = 'list')
    {
        $queryBuilder = $this->getModelManager()->getEntityManager($this->getClass())->createQueryBuilder();

        $queryBuilder->select('p')
            ->from($this->getClass(), 'p')
            ->leftJoin('p.category', 'c')
            ->orderby('c.title, p.title')
        ;

        $proxyQuery = new ProxyQuery($queryBuilder);
        return $proxyQuery;
    }

    protected function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        if (in_array($action, ['create'])) {
            return;
        }

        $admin = $this->isChild() ? $this->getParent() : $this;

        $id = $admin->getRequest()->get('id');

        if ($id !== null) {
            $menu->addChild('edit', [
                'uri' => $admin->generateUrl('edit', ['id' => $id]),
                'label' => 'Bearbeiten',
                'attributes' => ['icon' => 'fa fa-edit'],
            ]);

            $menu->addChild('execute', [
                'uri' => $admin->generateUrl('execute', ['id' => $id]),
                'label' => 'Query ausführen',
                'attributes' => ['icon' => 'fa fa-bolt']
            ]);

            $menu->addChild('subscriptions', [
                'uri' => $admin->generateUrl('invit.admin.data_analysis_query_subscription.list', ['id' => $id]),
                'label' => 'Abo',
                'attributes' => ['icon' => 'fa fa-clock-o']
            ]);
        } else {
            foreach ($this->getModelManager()->getEntityManager(DataAnalysisCategory::class)->getRepository(DataAnalysisCategory::class)->findBy([], ['title' => 'ASC']) as $category) {
                $menu->addChild('category_'.$category->getId(), [
                    'label' => $category->getTitle(),
                    'attributes' => ['dropdown' => 'true', 'class' => 'nav-header exp', 'data-category' => $category->getId()]
                ]);

                foreach ($this->getModelManager()->getEntityManager(DataAnalysisQuery::class)->getRepository(DataAnalysisQuery::class)->findBy(['category' => $category], ['title' => 'ASC']) as $query) {
                    $menu['category_'.$category->getId()]->addChild('execute'.$query->getId().$query->getId(), [
                        'attributes' => [
                            'class' => 'exp-hidden exp-menu cat-'.$category->getId(),
                            'icon' => 'fa '. ($query->getIcon() !== null ? $query->getIcon() : 'fa-angle-right')
                        ],
                        'uri' => $admin->generateUrl('execute', ['id' => $query->getId()]),
                        'label' => $query->getTitle()
                    ]);
                }
            }
        }
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        return;
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
            ->add('icon', IconType::class, ['label' => 'Icon', 'required' => false])
            ->add('description', null, ['label' => 'Beschreibung'])
            ->add('parameters', 'sonata_type_collection', [
                'label' => 'Parameter',
                'by_reference' => false,
            ], [
                'edit' => 'inline',
                'inline' => 'table',
            ])
            ->add('queryLanguage', null, ['label' => 'Query-Language'])
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
        $collection->add('execute', $this->getRouterIdParameter().'/query/execute');
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
