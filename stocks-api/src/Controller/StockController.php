<?php

namespace App\Controller;

use App\Client\AlphaVantageClient;
use App\Event\RequestCreatedEvent;
use App\Transformer\StockResponseTransformer;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

class StockController extends AbstractController
{
    public function __construct(
        protected readonly AlphaVantageClient $client,
        protected readonly StockResponseTransformer $stockResponseTransformer,
        protected readonly LoggerInterface $logger
    ) {
    }

    public function show(Request $request, EventDispatcherInterface $dispatcher): JsonResponse
    {
        $symbol = $request->query->get('q');

        if (empty($symbol)) {
            return $this->json(['message' => 'symbol is required.'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $responseData = $this->client->getGlobalQuote($symbol);
            $globalQuoteData = $responseData["Global Quote"] ?? null;

            if (empty($globalQuoteData)) {
                return $this->json([]);
            }

            $globalQuoteData['symbol'] = $symbol;

            $returnData = $this->stockResponseTransformer->transform($globalQuoteData);

            $dispatcher->dispatch(
                new RequestCreatedEvent($this->getUser(), 'stock_quote', $returnData),
                RequestCreatedEvent::NAME
            );

            return $this->json($returnData);

        } catch (ExceptionInterface $e) {
            $this->logger->error($e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return $this->json([], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
