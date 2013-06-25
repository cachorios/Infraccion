<?php

namespace Infraccion\infraccionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MunicipioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigo','integer',array('label' =>'Código'))
            ->add('nombre')


            ->add('email','email',array('label' =>'eMail'))
            ->add('telefono')
            ->add('direccion')
            ->add('localidad')
            ->add('provincia')

            ->add('logo','hidden',array(
                'larutils_enabled' => true,
//                'div_img' => 'foto',
                'larutils' => array(
                    'uploader'  =>'lar_utils_upload',
                    'folderUpload' => 'uploads',
                    'queueID' => 'fotoUp',
                    'buttonText' => 'Cambiar Logo',
                    'width'     => '100%',
                    'height'    => 28),
                'read_only' => true
            ))
            ->add('codigoPostal',null,array('label' =>'Código Postal'))
            ->add('num_cedula',null,array('label' => "Siguiente Cedula"))
            ->add('unidad_fiscal',null,array('label' => "Valor Unidad Fiscal"))


            ->add('cont1Nombre',null,array('label' =>'Apellido y Nombre'))
            ->add('cont1Cargo',null,array('label' =>'Cargo'))
            ->add('cont1Celular',null,array('label' =>'Nro. de Celular'))
            ->add('cont1Telfijo',null,array('label' =>'Nro. Tel. fijo'))
            ->add('cont1Email',"email",array('label' =>'eMail'))
            ->add('cont2Nombre',null,array('label' =>'Apellido y Nombre'))
            ->add('cont2Cargo',null,array('label' =>'Cargo'))
            ->add('cont2Celular',null,array('label' =>'Nro. de Celular'))
            ->add('cont2Telfijo',null,array('label' =>'Nro. Tel. fijo'))
            ->add('cont2Email',"email",array('label' =>'eMail', 'required' => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Infraccion\infraccionBundle\Entity\Municipio'
        ));
    }

    public function getName()
    {
        return 'infraccion_infraccionbundle_municipiotype';
    }
}
