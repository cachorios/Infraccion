<?php

namespace Infraccion\infraccionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FiltroParmType extends AbstractType
{
//    private $rep;
//    private $muni;
//    public function __construct(){
//
//    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

//        $reg = $builder->getData();
//        if($this->muni)
//            $muni = $this->muni;
//        elseif ($reg->getMunicipio()) {
//            $muni = $reg->getMunicipio()->getId();
//        }else{
//            $muni = 1;
//        }

        $builder
            ->add('municipio', 'entity', array(
                'class' => "InfraccionBundle:Municipio",
                'required' => true
            ))

            ->add('fecha_desde', "date", array(
                'label' => "Fecha de Proceso (dd/mm/aaaa)",
                'widget' => "singletext",
                'format' => 'dd/MM/yyyy',
                'help_block' => "dd/mm/aaaa"
            ))

            ->add('fecha_hasta', "date", array(
                'label' => "Fecha de Proceso (dd/mm/aaaa)",
                'widget' => "singletext",
                'format' => 'dd/MM/yyyy',
                'help_block' => "dd/mm/aaaa"
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Infraccion\infraccionBundle\Entity\Infraccion'
        ));
    }

    public function getName()
    {
        return 'infraccion_infraccionbundle_FiltroParmtype';
    }
}
