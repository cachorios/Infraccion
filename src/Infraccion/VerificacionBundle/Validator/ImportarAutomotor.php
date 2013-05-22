<?php


namespace Infraccion\VerificacionBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @api
 */
class ImportarAutomotor extends Constraint
{
    public $message = 'Archivo no Permitido';
}
