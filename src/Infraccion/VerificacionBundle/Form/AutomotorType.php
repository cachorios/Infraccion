<?php

namespace Infraccion\VerificacionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AutomotorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('id',"hidden")
            ->add('dominio',"hidden")
            ->add('marca')
            ->add('modelo')
            ->add('dni',null,array("label" => "Nro. de Docuemento") )
            ->add('cuitCuil',null,array("label" => "Nro. de Cui(t/l)") )
            ->add('nombre',null,array("label" => "Razon Social") )
            ->add('domicilio')
            ->add('codigoPostal',null,array("label" => "Cod. Postal") )
            ->add('provincia')
            ->add('localidad')
           /* ->add('ultimaActualizacion')
            ->add('fechaPedido')*/
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Infraccion\VerificacionBundle\Entity\Automotor'
        ));
    }

    public function getName()
    {
        return 'infraccion_verificacionbundle_automotortype';
    }
}
