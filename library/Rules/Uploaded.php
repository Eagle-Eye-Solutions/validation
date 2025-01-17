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

class Uploaded extends AbstractRule
{
    public function validate($input): bool
    {
        if ($input instanceof \SplFileInfo) {
            $input = $input->getPathname();
        }

        return is_string($input) && is_uploaded_file($input);
    }
}
