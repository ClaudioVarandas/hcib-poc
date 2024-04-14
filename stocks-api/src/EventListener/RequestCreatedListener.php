<?php

namespace App\EventListener;

use App\Entity\History;
use App\Event\RequestCreatedEvent;
use App\Message\RequestStockQuoteNotification;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\MessageBusInterface;
use function Symfony\Component\Clock\now;

class RequestCreatedListener
{
    public function __construct(
        private readonly ManagerRegistry $doctrine,
        private readonly MessageBusInterface $messageBus
    ) {
    }

    public function __invoke(RequestCreatedEvent $event): void
    {
        $em = $this->doctrine->getManager();

        $payloadData = $event->getData();

        $historyRecord = new History();
        $historyRecord->setDate(now());
        $historyRecord->setSymbol($payloadData['symbol']);
        $historyRecord->setOpen( $payloadData['open']);
        $historyRecord->setHigh($payloadData['high']);
        $historyRecord->setLow($payloadData['low']);
        $historyRecord->setClose($payloadData['close']);
        $historyRecord->setUser($event->getUser());

        $em->persist($historyRecord);
        $em->flush();

        $email = $event->getUser()->getEmail();

        // Notify user by email
        $this->messageBus->dispatch(
            new RequestStockQuoteNotification(
                email: $email,
                content: $event->getData()
            )
        );

    }

}