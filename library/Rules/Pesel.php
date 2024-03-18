<?php

namespace Respect\Validation\Rules;

class Pesel extends AbstractRule
{
    public function validate($input)
    {
        $stringInput = (string)$input;
        if (!preg_match('/^\d{11}$/', $stringInput)) {
            return false;
        }

        $weights = [1, 3, 7, 9, 1, 3, 7, 9, 1, 3];
        $targetControlNumber = $stringInput[10];
        $calculateControlNumber = 0;
        for ($i = 0; $i < 10; ++$i) {
            $calculateControlNumber += $stringInput[$i] * $weights[$i];
        }

        $calculateControlNumber = (10 - $calculateControlNumber % 10) % 10;

        return $targetControlNumber == $calculateControlNumber;
    }
}
