<?php

namespace Jarek\Service;

use Jarek\DatabaseAbstractionLayer\Entity\ProviderCollection;
use Jarek\DatabaseAbstractionLayer\Entity\ProviderEntity;
use Jarek\DatabaseAbstractionLayer\ProviderRepositoryInterface;
use Symfony\Component\HttpFoundation\InputBag;

class RecommendationService
{
    private array $topics;
    private ProviderCollection $providerCollection;

    private static array $singleTopicWeights = [1 => 0.2, 2 => 0.25, 3 => 0.3];
    private static float $twoTopicsWeight = 0.1;
    private static int $topicsToConsider = 3;

    public function __construct(private readonly ProviderRepositoryInterface $providerRepository)
    {
    }

    public function getQuotes(InputBag $dataBag): ?array
    {
        $topics = $dataBag->all();
        $this->topics = $this->getTopTopics($topics['topics']);
        $this->providerCollection = $this->providerRepository->getProviderData();
        return $this->calculateQuotes();
    }

    private function getTopTopics(array $topics): array
    {
        arsort($topics);
        return array_slice($topics, 0, self::$topicsToConsider);
    }

    private function calculateQuotes(): array {

        $quotes = [];
        /** @var ProviderEntity $provider */
        foreach($this->providerCollection as $provider) {
            $matchedTopics = 0;
            $matchedTopicsWeight = 0;
            $importance = 0;
            $singleTopicWeight = 0;
            foreach ($this->topics as $topic => $weight) {
                $importance++;
                if ($provider->getTopicCollection()->has($topic) && $matchedTopics < 2) {
                    $matchedTopicsWeight += self::$twoTopicsWeight * $weight;
                    if ($matchedTopics < 1) {
                        $singleTopicWeight = self::$singleTopicWeights[$importance] * $weight;
                    }
                    $matchedTopics++;
                }
            }
            if ($matchedTopics == 2 && $matchedTopicsWeight > 0) {
                $quotes[$provider->getName()] = $matchedTopicsWeight;
            } elseif ($matchedTopics == 1 && $singleTopicWeight > 0) {
                $quotes[$provider->getName()] = $singleTopicWeight;
            }
        }
        return $quotes;
    }
}