<?php declare(strict_types=1);

namespace Jarek\Subscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

readonly class ExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => 'onException',];
    }

    public function onException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        // Log the exception
        $this->logger->error('Caught exception: ' . $exception->getMessage(), ['exception' => $exception, 'trace' => $exception->getTraceAsString(),]);

        $response = new JsonResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);

        $event->setResponse($response);
        $event->stopPropagation();
    }
}
