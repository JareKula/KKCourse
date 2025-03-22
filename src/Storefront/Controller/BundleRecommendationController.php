<?php declare(strict_types=1);

namespace Jarek\Storefront\Controller;

use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(defaults: ['_routeScope' => ['storefront']])]
class BundleRecommendationController extends StorefrontController
{
    #[Route(
        path: '/course/bundle-recommendation',
        name: 'frontend.course.bundle-recommendation',
        methods: ['GET', 'POST']
    )]
    public function bundleRecommendation(Request $request, SalesChannelContext $context): JsonResponse
    {
        return new JsonResponse(['timestamp' => (new \DateTime())->format(\DateTimeInterface::W3C)]);
    }
}
