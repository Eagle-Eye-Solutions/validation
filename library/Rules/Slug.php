<?php

/*
 * This file is part of Respect/Validation.
 *
 * (c) Alexandre Gomes Gaigalas <alexandre@gaigalas.net>
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

namespace Respect\Validation\Rules;

class Slug extends AbstractRule
{
    public function validate($input)
    {
        if (mb_strstr((string) $input, '--')) {
            return false;
        }

        if (!preg_match('@^[0-9a-z\-]+$@', (string) $input)) {
            return false;
        }

        if (preg_match('@^-|-$@', (string) $input)) {
            return false;
        }

        return true;
    }
}
