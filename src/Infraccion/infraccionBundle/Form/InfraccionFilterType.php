<?php

namespace Infraccion\infraccionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;

class InfraccionFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dominio', 'filter_text')
            ->add('municipio','filter_number_range')
            //->add('municipio','filter_entity', array("class" => "InfraccionBundle:Municipio", "required" =>true))
            //->add('ubicacion','filter_entity', array("class" => "InfraccionBundle:Ubicacion", "required" =>true))
            //->add('tipo_infraccion','filter_entity', array("class" => "InfraccionBundle:TipoInfraccion", "required" =>true))
            ->add('fecha',"filter_date_range")
            ->add('etapa',"filter_number_range")
        ;



        $listener = function(FormEvent $event)
        {
            // Is data empty?
            foreach ($event->getData() as $data) {
                if(is_array($data)) {
                    foreach ($data as $subData) {
                        if(!empty($subData)) return;
                    }
                }
                else {
                    if(!empty($data)) return;
                }
            }

            $event->getForm()->addError(new FormError('Filter empty'));
        };
        $builder->addEventListener(FormEvents::POST_BIND, $listener);
    }

    public function getName()
    {
        return 'infraccion_infraccionbundle_infraccionfiltertype';
    }

//
//    public function getDefaultOptions(array $options)
//    {
//        return array(
//            'validation' => array('no_validation')
//        );
//    }
}
