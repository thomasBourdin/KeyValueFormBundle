<?php

namespace thomasBourdin\Bundle\KeyValueFormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ArrayChoiceList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class KeyValueRowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (null === $options['allowed_keys']) {
            $builder->add('key', $options['key_type'], $options['key_options']);
        } else {
            $builder->add('key', ChoiceType::class, array_merge(array(
                'choices' => $options['allowed_keys']
            ), $options['key_options']
            ));
        }

        $builder->add('value', $options['value_type'], $options['value_options']);
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'partitech_key_value_row';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // check if Form component version 2.8+ is used
        $isSf28 = method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix');

        $resolver->setDefaults(array(
            'key_type' => $isSf28 ? 'Symfony\Component\Form\Extension\Core\Type\TextType' : 'text',
            'key_options' => array(),
            'value_options' => array(),
            'allowed_keys' => null
        ));

        $resolver->setRequired(array('value_type'));

        if (method_exists($resolver, 'setDefined')) {
            // Symfony 2.6+ API
            $resolver->setAllowedTypes('allowed_keys', array('null', 'array'));
        } else {
            // Symfony <2.6 API
            $resolver->setAllowedTypes(array('allowed_keys' => array('null', 'array')));
        }
    }
}
