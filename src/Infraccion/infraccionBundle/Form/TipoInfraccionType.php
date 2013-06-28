<?php

namespace Infraccion\infraccionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TipoInfraccionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigo')
            ->add('nombre')
            ->add('cantidad_foto',"choice", array(
                'choices' => array(1 => 1,2 =>2, 3=>3 ),
                'required' => true,
                'label' => "Cantidad de Fotos",
                'expanded' => true,

            ))
            ->add('importe',null,array("label" => "Importe (en unidad fiscal)"))
            ->add('observacion')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Infraccion\infraccionBundle\Entity\TipoInfraccion'
        ));
    }

    public function getName()
    {
        return 'infraccion_infraccionbundle_tipoinfracciontype';
    }
}
