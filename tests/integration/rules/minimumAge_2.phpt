--FILE--
<?php
require 'vendor/autoload.php';

date_default_timezone_set('UTC');

use Respect\Validation\Exceptions\MinimumAgeException;
use Respect\Validation\Validator as v;

try {
    v::MinimumAge(12)->check((new \DateTime('-10 Years'))->format('d/m/Y'));
} catch (MinimumAgeException $exception) {
    echo $exception->getMainMessage();
}

?>
--EXPECTF--
The age must be 12 years or more.
