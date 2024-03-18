<?php

declare(strict_types=1);

namespace Respect\Validation\Rules;

use SplFileInfo;

class Executable extends AbstractRule
{
    public function validate($input)
    {
        if ($input instanceof SplFileInfo) {
            return $input->isExecutable();
        }
        if (!is_scalar($input)) {
            return false;
        }

        return is_executable((string)$input);
    }
}
