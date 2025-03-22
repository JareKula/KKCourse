<?php

namespace Jarek\Service;

use Jarek\DatabaseAbstractionLayer\Entity\ProviderCollection;
use Jarek\DatabaseAbstractionLayer\Entity\ProviderEntity;
use Jarek\DatabaseAbstractionLayer\Entity\TopicCollection;
use Jarek\DatabaseAbstractionLayer\ProviderRepository;
use Jarek\DatabaseAbstractionLayer\TopicRepository;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;

class RecommendationService
{
    private array $topics;
    private ProviderCollection $providerCollection;
    private TopicCollection $topicCollection;

    private static array $singleTopicWeights = [0 => 0.2, 1 => 0.25, 2 => 0.3];

    public function __construct(private readonly TopicRepository $topicRepository, private readonly ProviderRepository $providerRepository)
    {
    }

    public function getQuotes(RequestDataBag $dataBag): ?array
    {
        $this->topics = $this->getTopTopics($dataBag);
        $this->topicCollection = $this->topicRepository->getDataFromJsonFile('CourseBundleRecommendation/providers.json');
        $this->providerCollection = $this->providerRepository->getDataFromJsonFile('CourseBundleRecommendation/providers.json');
        return $this->calculateQuotes();
    }

    private function getTopTopics(RequestDataBag $dataBag): array
    {
        $topics = $dataBag->all();
        arsort($topics);
        return array_slice($topics, 0, 3);
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
                if ($provider->getTopicCollection()->has($topic) && $matchedTopics < 2) {
                    $matchedTopicsWeight += 0.1 * $weight;
                    if ($matchedTopics < 1) {
                        $singleTopicWeight = self::$singleTopicWeights[$importance] * $weight;
                    }
                    $matchedTopics++;
                }
                $importance++;
            }
            if ($matchedTopics == 2 && $matchedTopicsWeight > 0) {
                $quotes[] = [$provider->getName() => $matchedTopicsWeight];
            } elseif ($matchedTopics == 1 && $singleTopicWeight > 0) {
                $quotes[] = [$provider->getName() => $singleTopicWeight];
            }
        }
        return $quotes;
    }
}