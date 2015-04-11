<?php
/**
 * Created by PhpStorm.
 * User: damien
 * Date: 11/04/15
 * Time: 10:27
 */

namespace DocManager\DocumentBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class DateConstraint extends Constraint{

    public $message = "La date d'expiration maximale est de 5 ans à partir d'aujourd'hui. Seuls les utilisateurs premium peuvent aller au delà, ou ne pas en fixer";

    public function validatedBy()
    {
        return 'date_validator';
    }
}