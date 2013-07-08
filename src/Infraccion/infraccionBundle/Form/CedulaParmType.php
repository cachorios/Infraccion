<?php

namespace Infraccion\infraccionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CedulaParmType extends AbstractType
{
    private $session;
    public function __construct($session){
        $this->session = $session;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $dataFiltro = $this->session->get('InfraccionFilterParm');
        if($dataFiltro){
            $m =  $dataFiltro->getMunicipio()->getId();
            $f1 = $dataFiltro->getFecha()->setTime(0,0);
            $f2 = $dataFiltro->getFecha()->setTime(23,59);
        }else{
            $f1 = new \DateTime('now');
            $f2 = new \DateTime('now');
            $m=null;
        }


        $builder
            ->add('municipio', 'entity', array(
                'class' => "InfraccionBundle:Municipio",
                'required' => true,
                'data' => $m
            ))

            ->add('fecha_desde', "date", array(
                'label' => "Fecha de Proceso (dd/mm/aaaa)",
                'widget' => "single_text",
                'format' => 'dd/MM/yyyy',
                'help_block' => "dd/mm/aaaa",
                'data' => $f1
            ))

            ->add('fecha_hasta', "date", array(
                'label' => "Fecha de Proceso (dd/mm/aaaa)",
                'widget' => "single_text",
                'format' => 'dd/MM/yyyy',
                'help_block' => "dd/mm/aaaa",
                'data' => $f2
            ))
            ->add('primer_vencimiento', "date", array(
                'widget' => "single_text",
                'format' => 'dd/MM/yyyy',
                'help_block' => "dd/mm/aaaa"
            ))

            ->add('segundo_vencimiento', "date", array(
                'widget' => "single_text",
                'format' => 'dd/MM/yyyy',
                'help_block' => "dd/mm/aaaa"
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'allow_extra_fields' => true
        ));
    }

    public function getName()
    {
        return 'infraccion_infraccionbundle_CedulaParmtype';
    }
}
