<?php declare(strict_types=1);

namespace Jarek\DatabaseAbstractionLayer;

use Jarek\DatabaseAbstractionLayer\Entity\TopicEntity;
use Jarek\DatabaseAbstractionLayer\Entity\TopicCollection;
use RuntimeException;
use League\Flysystem\Filesystem;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class TopicRepository
{
    public function __construct(#[Autowire(service: 'shopware.filesystem.private')] private Filesystem $filesystem)
    {
    }

    public function getDataFromJsonFile(string $fileName): TopicCollection
    {
        $topicEntityCollection = new TopicCollection();
        try {
            $fileContent = $this->filesystem->read($fileName);
        } catch (\Throwable $exception) {
            throw new RuntimeException($exception->getMessage());
        }

        $jsonData = json_decode($fileContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Invalid JSON data.');
        }

        if (empty($jsonData['provider_topics'])) {
            throw new RuntimeException('Invalid JSON data structure.');
        }

        foreach ($jsonData['provider_topics'] as $provider) {
            $topicNames = explode("+", $provider);
            foreach ($topicNames as $topicName) {
                if (empty($topicName)) {
                    throw new RuntimeException('Invalid JSON data structure - incorrect topic names.');
                }
                $topicEntity = new TopicEntity();
                $topicEntity->setName($topicName);
                $topicEntityCollection->add($topicEntity);
            }
        }
        return $topicEntityCollection;
    }
}