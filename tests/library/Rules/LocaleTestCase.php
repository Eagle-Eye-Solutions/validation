<?php

/*
 * This file is part of Respect/Validation.
 *
 * (c) Alexandre Gomes Gaigalas <alexandre@gaigalas.net>
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

namespace Respect\Validation\Test\Rules;

use malkusch\bav\BAV;
use malkusch\bav\ConfigurationRegistry;
use malkusch\bav\DataBackend;
use malkusch\bav\DataBackendContainer;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LocaleTestCase extends TestCase
{
    protected function getBavMock(): MockObject
    {
        return $this->getMockBuilder(BAV::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $dataBackend = $this->getMockForAbstractClass(DataBackend::class);
        $dataBackendContainer = $this->getMockForAbstractClass(DataBackendContainer::class);
        $dataBackendContainer->method('makeDataBackend')
            ->willReturn($dataBackend);

        ConfigurationRegistry::getConfiguration()->setDataBackendContainer($dataBackendContainer);
        ConfigurationRegistry::getConfiguration()->setUpdatePlan(null);
    }
}
