<?php

namespace Infraccion\VerificacionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;

class AutomotorFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'filter_number_range')
            ->add('dominio', 'filter_text')
            ->add('marca', 'filter_text')
            ->add('modelo', 'filter_text')
            ->add('dni', 'filter_text')
            ->add('cuitCuil', 'filter_text')
            ->add('nombre', 'filter_text')
            ->add('domicilio', 'filter_text')
            ->add('codigoPostal', 'filter_text')
            ->add('provincia', 'filter_text')
            ->add('localidad', 'filter_text')
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
        return 'infraccion_verificacionbundle_automotorfiltertype';
    }
}
