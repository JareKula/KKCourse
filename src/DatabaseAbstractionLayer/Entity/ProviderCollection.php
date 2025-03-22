<?php declare(strict_types=1);

namespace Jarek\DatabaseAbstractionLayer\Entity;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

class ProviderCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return ProviderEntity::class;
    }
}