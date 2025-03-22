<?php declare(strict_types=1);

namespace Jarek\DatabaseAbstractionLayer\Entity;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;

class ProviderEntity extends Entity
{
    protected ?string $name;
    protected TopicCollection $topicCollection;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
        $this->_uniqueIdentifier = $name;
    }

    public function getTopicCollection(): ?TopicCollection
    {
        return $this->topicCollection;
    }

    public function setTopicCollection(TopicCollection $topicCollection): void
    {
        $this->topicCollection = $topicCollection;
    }
}