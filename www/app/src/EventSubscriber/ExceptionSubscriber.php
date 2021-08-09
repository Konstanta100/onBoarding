<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Exception\ApiException;
use App\Exception\ValidationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;


class ExceptionSubscriber implements EventSubscriberInterface
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $event->allowCustomResponseCode();
        $throwable = $event->getThrowable();

        if($throwable instanceof ApiException){

            $data['message'] = $throwable->getMessage();

            if ($throwable instanceof ValidationException){
                $data['errors'] = $throwable->getErrors();
            }

            $data = $this->serializer->serialize($data, 'json');

            $event->setResponse(new JsonResponse($data, $throwable->getCode(), [], true));
        }
    }
}