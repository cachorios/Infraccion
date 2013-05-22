<?php

namespace  Infraccion\VerificacionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Doctrine\ORM\EntityRepository;

class ImportarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("file","file", array("required"=>true, 'label' => 'Archivo'))
            ->add("fila","integer", array("required"=>true, 'label' => 'Fila'))
            ->add("columna","text", array("required"=>true, 'label' => 'Colunmna'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Infraccion\VerificacionBundle\Entity\Importar'
        ));
    }

    public function getName()
    {
        return 'infraccion_verificacionbundle_importartype';
    }
}
