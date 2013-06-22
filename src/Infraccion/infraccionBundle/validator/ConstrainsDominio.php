<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cacho
 * Date: 21/06/13
 * Time: 10:13
 * To change this template use File | Settings | File Templates.
 */

namespace Infraccion\infraccionBundle\validator;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ConstrainsDominio extends Constraint {
    public $message = "El dominio contiene catacteres ilegales";
}