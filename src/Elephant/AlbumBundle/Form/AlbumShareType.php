<?php

namespace Elephant\AlbumBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AlbumShareType extends AlbumType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add(
            'shares',
            'collection',
            array(
                'type' => new ShareType(),
                'allow_add' => true,
                'by_reference' => false,
                'options' => array('label' => false),
                )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'elephant_albumbundle_album_share';
    }
}
