<?php
/**
 * Created by PhpStorm.
 * User: damien
 * Date: 11/04/15
 * Time: 10:30
 */

namespace DocManager\DocumentBundle\Validator\Constraints;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class DateConstraintValidator extends ConstraintValidator{

    private $securityContext;

    public function __construct(SecurityContext $securityContext)
    {
        $this->securityContext = $securityContext;
    }
    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     *
     * @api
     */
    public function validate($value, Constraint $constraint)
    {
        $user = $this->securityContext->getToken()->getUser();
        if(! $user->hasRole('ROLE_PREMIUM'))
        {
            $dateLimite = new \DateTime();
            $dateLimite->modify("+5 years");
            if($value > $dateLimite)
            {
                $this->context->addViolation($constraint->message);
            }
        }
    }
}