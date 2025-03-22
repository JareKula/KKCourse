<?php declare(strict_types=1);

namespace Jarek\Storefront\Controller;

use Jarek\Service\RecommendationService;
use RuntimeException;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route(defaults: ['_routeScope' => ['storefront']])]
class BundleRecommendationController extends StorefrontController
{

    public function __construct(private readonly RecommendationService $recommendationService) {

    }

    #[Route(
        path: '/course/bundle-recommendation',
        name: 'frontend.course.bundle-recommendation',
        defaults: ['XmlHttpRequest' => true],
        methods: ['GET', 'POST']
    )]
    public function bundleRecommendation(RequestDataBag $dataBag, SalesChannelContext $context): JsonResponse
    {
        if (!$dataBag->has('topics')) {
            throw new RuntimeException('Malformed input');
        }
        $quotes = $this->recommendationService->getQuotes($dataBag->get('topics'));
        return new JsonResponse($quotes);
    }

}
