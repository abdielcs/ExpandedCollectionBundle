<?php

/*
 * This file is part of the ExpandedCollectionBundle.
 *
 * (c) Abdiel Carrazana <abdielcs@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace abdielcs\ExpandedCollectionBundle\Form\Type;

use abdielcs\ExpandedCollectionBundle\Form\DataTransformer\MiddleClassTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Custom form type for render expanded entities collections in a ManyToMany association mapping.
 *
 * @author Abdiel Carrazana <abdielcs@gmail.com>
 */
class ExpandedMTMType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entityTransformer = new MiddleClassTransformer(
            $options['class'],
            $options['middle_class']
        );
        $builder->addModelTransformer($entityTransformer);
    }

    public function getParent()
    {
        if (method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix')) {
            return ExpandedOTMType::class;
        } else {
            return 'expanded_otm';
        }
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'expanded_mtm';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array(
            'middle_class'
        ));
    }

}
