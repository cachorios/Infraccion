<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cacho
 * Date: 28/06/13
 * Time: 21:09
 * To change this template use File | Settings | File Templates.
 */

namespace Lar\UsuarioBundle\Twig;


class LarExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'cuitformat' => new \Twig_Filter_Method($this, 'cuitformatFilter'),
        );
    }

    public function getName()
    {
        return 'lar_extension';
    }


    public function cuitformatFilter($number)
    {

        return LarExtension::cuitformat($number);
    }

    static public function cuitformat($number)
    {
        if( strlen($number) == 11){
            $s = substr($number,0,2);
            $d = substr($number,2,8);
            $v = substr($number,10,1);
            $cuit = $s ."-". substr('0000000000'. number_format($d,0, ',', '.'),-10).'/'.$v ;

            return $cuit;
        }else{
            return "//";
        }

    }
}