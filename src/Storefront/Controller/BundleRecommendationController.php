<?php declare(strict_types=1);

namespace Jarek\Storefront\Controller;

use InvalidArgumentException;
use Jarek\Service\RecommendationService;
use Psr\Log\LoggerInterface;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(defaults: ['_routeScope' => ['storefront']])]
class BundleRecommendationController extends StorefrontController
{

    public function __construct(private readonly RecommendationService $recommendationService, private readonly LoggerInterface $logger)
    {

    }

    #[Route(path: '/course/bundle-recommendation', name: 'frontend.course.bundle-recommendation', defaults: ['XmlHttpRequest' => true], methods: ['GET', 'POST'])]
    public function bundleRecommendation(Request $request, SalesChannelContext $context): JsonResponse
    {
        $this->logger->debug("Recommendation request: " . json_encode($request->toArray()));
        $dataBag = $request->getPayload();
        if (!$dataBag->has('topics')) {
            $this->logger->error("Recommendation request: Malformed input.");
            throw new InvalidArgumentException('Malformed input.');
        }
        $quotes = $this->recommendationService->getQuotes($dataBag);
        $this->logger->debug("Recommendation response: " . json_encode(['quotes' => $quotes]));
        return new JsonResponse(['quotes' => $quotes]);
    }

}
