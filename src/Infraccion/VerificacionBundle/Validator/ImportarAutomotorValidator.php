<?php

namespace Infraccion\VerificacionBundle\Validator;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ImportarAutomotorValidator extends ConstraintValidator
{
    /**
     * Checks if an uploaded file
     */
    public function isValid($value, Constraint $constraint)
    {
        $isValid = false;
        if ($value instanceof UploadedFile) {

            $nombreOriginal = $value->getClientOriginalName();
            if ($value->getClientMimeType() ==  "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"){
                $isValid = true;
            }elseif($value->getClientMimeType() ==  "application/vnd.ms-excel"){
                $isValid = true;
            }

        }

        if (!$isValid) {

            $this->context->addViolation($constraint->message, array('{{ value }}' => $value));
        }

        return $isValid;
    }
}