<?php

namespace Infraccion\infraccionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FiltroParmType extends AbstractType
{
    private $rep;
    private $muni;
    public function __construct($rep = null, $muni = null){
        $this->rep = $rep;
        $this->muni = $muni;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $reg = $builder->getData();
        if($this->muni)
            $muni = $this->muni;
        elseif ($reg->getMunicipio()) {
            $muni = $reg->getMunicipio()->getId();
        }else{
            $muni = 1;
        }

        $builder
            ->add('municipio', 'entity', array(
                'class' => "InfraccionBundle:Municipio",
                'required' => true
            ))
                ->add('ubicacion', 'entity', array(
                    'class' => "InfraccionBundle:Ubicacion",
                    'label' => "Ubicación",
                    'required' => true,
                    'query_builder' => $this->rep->getUbicacionByMuni($muni)
                ))
            ->add('tipo_infraccion', 'entity', array(
                'class' => "InfraccionBundle:TipoInfraccion",
                'label' => "Tipo de Infracción",
                'required' => true
            ))
            ->add('fecha', "date", array(
                'label' => "Fecah de Proceso (dd - mm - aaaa)",
                'widget' => "text",
                'format' => 'dd-MM-yyyy',
                'help_block' => "dd-mm-aaaa"
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
