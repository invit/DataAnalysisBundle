<?php

namespace Invit\DataAnalysisBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DataAnalysisQueryParameterType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options){

        if(null !== $options['queryObject']){
            foreach($options['queryObject']->getParameters() as $parameter){

                if(null === $parameter->getSelection()){
                    $builder->add($parameter->getName(), null, ['label' => $parameter->getTitle()]);
                }elseif(preg_match('/^\{(.*)\}$/', $parameter->getSelection())){
                    $builder->add($parameter->getName(), 'choice', ['choices' => json_decode($parameter->getSelection(), true), 'label' => $parameter->getTitle()]);
                }elseif(preg_match("/^[a-zA-Z0-9]+\\\.*\\\[a-zA-Z0-9]+$/", $parameter->getSelection())){
                    $builder->add($parameter->getName(), 'entity', ['class' => $parameter->getSelection(), 'label' => $parameter->getTitle()]);
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'form';
    }


    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(['queryObject' => null, 'csrf_protection' => false]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'data_analysis_query_parameter_type';
    }
}
