<?php declare(strict_types=1);

namespace Jarek\Storefront\Controller;

use Jarek\DatabaseAbstractionLayer\ProviderRepository;
use Jarek\DatabaseAbstractionLayer\TopicRepository;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(defaults: ['_routeScope' => ['storefront']])]
class BundleRecommendationController extends StorefrontController
{
    public function __construct(private readonly TopicRepository $topicRepository, private readonly ProviderRepository $providerRepository)
    {
    }

    #[Route(
        path: '/course/bundle-recommendation',
        name: 'frontend.course.bundle-recommendation',
        methods: ['GET', 'POST']
    )]
    public function bundleRecommendation(Request $request, SalesChannelContext $context): JsonResponse
    {
        $topicCollection = $this->topicRepository->getDataFromJsonFile('CourseBundleRecommendation/providers.json');
        $providerCollection = $this->providerRepository->getDataFromJsonFile('CourseBundleRecommendation/providers.json');
        return new JsonResponse([$topicCollection, $providerCollection]);
    }
}
