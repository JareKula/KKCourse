<?php declare(strict_types=1);

namespace Jarek\DatabaseAbstractionLayer\Entity;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;

class TopicEntity extends Entity
{
    protected ?string $name;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
        $this->_uniqueIdentifier = $name;
    }
}