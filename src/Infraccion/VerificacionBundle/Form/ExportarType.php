<?php

namespace  Infraccion\VerificacionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


use Doctrine\ORM\EntityRepository;

class ExportarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaInicio', "date", array(
            'label' => "Fecha inicio del Proceso (dd - mm - aaaa)",
            'widget' => "text",
            'format' => 'dd-MM-yyyy',
            'help_block' => "dd-mm-aaaa"
        ))
            ->add('fechaFinal', "date", array(
            'label' => "Fecha final del Proceso (dd - mm - aaaa)",
            'widget' => "text",
            'format' => 'dd-MM-yyyy',
            'help_block' => "dd-mm-aaaa"

        ))
            ->add("usarFecha","checkbox", array("required"=>false, 'label' => 'Usar parametro Fechas', 'label_render' => true))
            ->add("usarNull","checkbox", array("required"=>false, 'label' => 'Usar registros Null'

        ))

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Infraccion\VerificacionBundle\Entity\Exportar'
        ));
    }

    public function getName()
    {
        return 'infraccion_verificacionbundle_exportartype';
    }
}
