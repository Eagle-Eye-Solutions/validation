<?php

namespace Respect\Validation\Rules;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;

class Email extends AbstractRule
{
    public function __construct(EmailValidator $emailValidator = null)
    {
        $this->emailValidator = $emailValidator;
    }

    public function getEmailValidator()
    {
        if (class_exists(EmailValidator::class)
            && !$this->emailValidator instanceof EmailValidator) {
            $this->emailValidator = new EmailValidator();
        }

        return $this->emailValidator;
    }

    public function validate($input)
    {
        $emailValidator = $this->getEmailValidator();
        if (!$emailValidator instanceof EmailValidator) {
            return is_string($input) && filter_var($input, FILTER_VALIDATE_EMAIL);
        }

        if (!class_exists(RFCValidation::class)) {
            return $emailValidator->isValid($input);
        }

        return $emailValidator->isValid($input, new RFCValidation());
    }
}
