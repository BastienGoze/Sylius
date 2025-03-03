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

namespace Sylius\Bundle\ApiBundle\Doctrine\QueryCollectionExtension;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\ContextAwareQueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ApiBundle\Context\UserContextInterface;
use Sylius\Bundle\ApiBundle\Serializer\ContextKeys;
use Sylius\Component\Core\Model\ProductInterface;
use Webmozart\Assert\Assert;

/** @experimental */
final class ProductsByChannelAndLocaleCodeExtension implements ContextAwareQueryCollectionExtensionInterface
{
    public function __construct(private UserContextInterface $userContext)
    {
    }

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null,
        array $context = [],
    ): void {
        if (!is_a($resourceClass, ProductInterface::class, true)) {
            return;
        }

        $user = $this->userContext->getUser();
        if ($user !== null && in_array('ROLE_API_ACCESS', $user->getRoles(), true)) {
            return;
        }

        Assert::keyExists($context, ContextKeys::CHANNEL);
        Assert::keyExists($context, ContextKeys::LOCALE_CODE);

        $channel = $context[ContextKeys::CHANNEL];
        $localeCode = $context[ContextKeys::LOCALE_CODE];

        $rootAlias = $queryBuilder->getRootAliases()[0];
        $localeCodeParameterName = $queryNameGenerator->generateParameterName('localeCode');
        $channelsParameterName = $queryNameGenerator->generateParameterName('channel');

        $queryBuilder
            ->addSelect('translation')
            ->innerJoin(
                sprintf('%s.translations', $rootAlias),
                'translation',
                'WITH',
                sprintf('translation.locale = :%s', $localeCodeParameterName),
            )
            ->andWhere(sprintf(':%s MEMBER OF %s.channels', $channelsParameterName, $rootAlias))
            ->setParameter($channelsParameterName, $channel)
            ->setParameter($localeCodeParameterName, $localeCode)
        ;
    }
}
