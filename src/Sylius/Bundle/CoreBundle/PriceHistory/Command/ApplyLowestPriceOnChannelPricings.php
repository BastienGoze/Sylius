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

namespace Sylius\Bundle\CoreBundle\PriceHistory\Command;

final class ApplyLowestPriceOnChannelPricings
{
    public function __construct(public array $channelPricingIds)
    {
    }
}
