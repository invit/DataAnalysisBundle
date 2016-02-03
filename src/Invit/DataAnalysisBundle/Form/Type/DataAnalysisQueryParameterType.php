<?php

namespace Invit\DataAnalysisBundle\Form\Type;

use Doctrine\ORM\EntityManagerInterface;
use Invit\DataAnalysisBundle\Entity\DataAnalysisQueryParameter;
use Invit\DataAnalysisBundle\Form\DataTransformer\ParameterValueTransformer;
use Invit\ToolsetBundle\Form\Type\TransliteratedSelect2Type;
use Sonata\CoreBundle\Form\Type\DatePickerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DataAnalysisQueryParameterType extends AbstractType
{
    private $em;

    /**
     * DataAnalysisQueryParameterType constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (null !== $options['query_object']) {
            foreach ($options['query_object']->getParameters() as $parameter) {
                switch ($parameter->getType()) {
                    case DataAnalysisQueryParameter::DATE_TYPE;
                        $builder->add($parameter->getName(), DatePickerType::class, [
                            'label' => $parameter->getTitle(),
                        ]);
                        break;
                    case DataAnalysisQueryParameter::CHOICE_TYPE;
                        $builder->add($parameter->getName(), ChoiceType::class, [
                            'choices' => json_decode($parameter->getSelection(), true),
                            'label' => $parameter->getTitle(),
                        ]);
                        break;
                    case DataAnalysisQueryParameter::ENTITY_TYPE;
                        $builder->add($parameter->getName(), TransliteratedSelect2Type::class, [
                            'class' => $parameter->getSelection(),
                            'label' => $parameter->getTitle()]
                        );
                        break;
                    default:
                        $builder->add($parameter->getName(), null, ['label' => $parameter->getTitle()]);
                        break;
                }
            }
            if ($options['normalize_values']) {
                $builder->addModelTransformer(new ParameterValueTransformer($this->em, $options['query_object']));
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return FormType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'query_object' => null,
            'normalize_values' => false,
            'csrf_protection' => false
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'data_analysis_query_parameter';
    }
}
