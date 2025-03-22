<?php declare(strict_types=1);

namespace Jarek\DatabaseAbstractionLayer;

use Jarek\DatabaseAbstractionLayer\Entity\ProviderCollection;
use Jarek\DatabaseAbstractionLayer\Entity\ProviderEntity;
use Jarek\DatabaseAbstractionLayer\Entity\TopicEntity;
use Jarek\DatabaseAbstractionLayer\Entity\TopicCollection;
use RuntimeException;
use League\Flysystem\Filesystem;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class ProviderRepository
{
    public function __construct(#[Autowire(service: 'shopware.filesystem.private')] private Filesystem $filesystem)
    {
    }

    public function getDataFromJsonFile(string $fileName): ProviderCollection
    {
        $providerCollection = new ProviderCollection();
        try {
            $fileContent = $this->filesystem->read($fileName);
        } catch (\Throwable $exception) {
            throw new RuntimeException($exception->getMessage());
        }

        $jsonData = json_decode($fileContent, true, 512);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Invalid JSON data.');
        }

        if (empty($jsonData['provider_topics'])) {
            throw new RuntimeException('Invalid JSON data structure.');
        }

        foreach ($jsonData['provider_topics'] as $providerName => $providerTopics) {
            if (empty($providerName)) {
                throw new RuntimeException('Invalid JSON data structure - incorrect provider name.');
            }
            $topicCollection = new TopicCollection();
            $topicNames = explode("+", $providerTopics);
            foreach ($topicNames as $topicName) {
                if (empty($topicName)) {
                    throw new RuntimeException('Invalid JSON data structure - incorrect topic names.');
                }
                $topicEntity = new TopicEntity();
                $topicEntity->setName($topicName);
                $topicCollection->add($topicEntity);
            }
            if ($topicCollection->count() == 0) {
                throw new RuntimeException('Invalid JSON data structure - provider without topics.');
            }
            $providerEntity = new ProviderEntity();
            $providerEntity->setName($providerName);
            $providerEntity->setTopicCollection($topicCollection);
            $providerCollection->add($providerEntity);
        }
        return $providerCollection;
    }
}