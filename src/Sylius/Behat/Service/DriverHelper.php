<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Behat\Service;

use Behat\Mink\Driver\DriverInterface;
use Behat\Mink\Driver\Selenium2Driver;
use DMore\ChromeDriver\ChromeDriver;

abstract class DriverHelper
{
    public static function isJavascript(DriverInterface $driver): bool
    {
        return $driver instanceof Selenium2Driver || $driver instanceof ChromeDriver;
    }

    public static function isNotJavascript(DriverInterface $driver): bool
    {
        return !$driver instanceof Selenium2Driver && !$driver instanceof ChromeDriver;
    }
}
