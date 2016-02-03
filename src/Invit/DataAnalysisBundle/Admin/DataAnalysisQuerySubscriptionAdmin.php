<?php

namespace Invit\DataAnalysisBundle\Admin;

use Invit\DataAnalysisBundle\Form\DataTransformer\ParameterValueTransformer;
use Invit\DataAnalysisBundle\Form\Type\DataAnalysisQueryParameterType;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class DataAnalysisQuerySubscriptionAdmin extends Admin
{
    public function getParentAssociationMapping()
    {
        return 'query';
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('schedule', null, ['template' => 'InvitDataAnalysisBundle:CRUD:list_subscription_schedule.html.twig'])
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('month', ChoiceType::class, [
                'label' => 'Monat',
                'required' => false,
                'empty_value' => 'monatlich',
                'choices' => [
                    1 => 'Januar',
                    2 => 'Februar',
                    3 => 'März',
                    4 => 'April',
                    5 => 'Mai',
                    6 => 'Juni',
                    7 => 'Juli',
                    8 => 'August',
                    9 => 'September',
                    10 => 'Oktober',
                    11 => 'November',
                    12 => 'Dezember',
                ]
            ])
            ->add('day', ChoiceType::class, [
                'label' => 'Tag',
                'required' => false,
                'empty_value' => 'täglich',
                'choices' => [
                    1 => 'Montag',
                    2 => 'Dienstag',
                    3 => 'Mittwoch',
                    4 => 'Donnerstag',
                    5 => 'Freitag',
                    6 => 'Samstag',
                    7 => 'Sonntag',
                ]
            ])
            ->add('hour', ChoiceType::class, [
                'label' => 'Stunde',
                'required' => false,
                'empty_value' => 'stündlich',
                'choices' => array_combine(range(1, 24), range(1, 24))
            ])
            ->add('minute', ChoiceType::class, [
                'label' => 'Minute',
                'required' => false,
                'empty_value' => 'jede Minute',
                'choices' => array_combine(range(1, 59), range(1, 59))
            ])
            ->add('channel', null, ['label' => 'Slack-Channel'])
            ->add('parameterValues', DataAnalysisQueryParameterType::class, [
                'label' => 'Parameter',
                'query_object' => $this->getParent()->getSubject(),
                'normalize_values' => true,
            ]);
    }

    public function getTemplate($name)
    {
        switch ($name) {
            case 'edit':
                return 'InvitDataAnalysisBundle:CRUD:edit_subscription_admin.html.twig';
                break;
            default:
                return parent::getTemplate($name);
                break;
        }
    }

    public function getFormTheme()
    {
        $formTheme = parent::getFormTheme();
        $formTheme[] = 'InvitDataAnalysisBundle:Form:fields.html.twig';
        return $formTheme;
    }
}
