<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cacho
 * Date: 21/06/13
 * Time: 10:20
 * To change this template use File | Settings | File Templates.
 */

namespace Infraccion\infraccionBundle\validator;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ConstrainsDominioValidator extends ConstraintValidator {
    public function validate($value, Constraint $constraint)
    {
        if (!preg_match('/^[A-Z][A-Z][A-Z][0-9][0-9][0-9]+$/', $value, $matches)) {
            $this->context->addViolation($constraint->message, array('%string%' => $value));
        }
    }
}