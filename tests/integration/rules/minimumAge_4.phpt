--FILE--
<?php
require 'vendor/autoload.php';

date_default_timezone_set('UTC');

use Respect\Validation\Exceptions\AllOfException;
use Respect\Validation\Validator as v;

try {
    v::MinimumAge(12)->assert((new \DateTime('-10 Years'))->format('d/m/Y'));
} catch (AllOfException $exception) {
    echo $exception->getFullMessage();
}

?>
--EXPECTF--
- The age must be 12 years or more.
