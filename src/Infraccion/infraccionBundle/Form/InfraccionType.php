<?php

namespace Infraccion\infraccionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InfraccionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dominio')
//            ->add('municipio')
//            ->add('ubicacion')
//            ->add('tipo_infraccion')
            ->add('fecha',"datetime",array('with_seconds'=> true ))
//            ->add('foto1')
//            ->add('foto2')
//            ->add('foto3')
            ->add('observacion')
//            ->add('etapa')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Infraccion\infraccionBundle\Entity\Infraccion'
        ));
    }

    public function getName()
    {
        return 'infraccion_infraccionbundle_infracciontype';
    }
}
