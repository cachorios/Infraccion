<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cacho
 * Date: 19/03/13
 * Time: 12:56
 * To change this template use File | Settings | File Templates.
 */

namespace Infraccion\FotoManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UploadfotoType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('path')
            ->add('foto','text',array(
                'larutils_enabled' => true,
                'div_img' => 'foto',
                'larutils' => array(
                    'uploader'  =>'foto_manager_up2server',
                    'folderUpload' => 'uploads/',
                    'formData' => "{'pathOrigen': ''",
                    'queueID' => 'fotoUp',
                    'buttonText' => 'Subir Fotos',
                    'width'     => '200px',
                    'height'    => 28),
                'read_only' => true
            ))
        ;
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
//        $resolver->setDefaults(array(
//            'data_class' => 'Lar\UsuarioBundle\Entity\Grupo'
//        ));
    }

    public function getName()
    {
        return 'uploadfoto';
    }
}





