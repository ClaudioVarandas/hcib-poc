<?php

namespace App\Controller;

use App\Event\RequestCreatedEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StockController extends AbstractController
{
    public function show(Request $request, EventDispatcherInterface $dispatcher): JsonResponse
    {
        $symbol = $request->query->get('q');

        if(empty($symbol)){
            return $this->json([],Response::HTTP_BAD_REQUEST);
        }

        /*$apiKey = $this->getParameter('alpha_vantage_api_key');
        $url = "https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol=$symbol&apikey=$apiKey";
        $json = file_get_contents($url);*/

        $json = '{"Global Quote":{"01. symbol":"300135.SHZ","02. open":"2.4700","03. high":"2.5100","04. low":"2.3900","05. price":"2.4400","06. volume":"38891270","07. latest trading day":"2024-03-01","08. previous close":"2.4500","09. change":"-0.0100","10. change percent":"-0.4082%"}}';
        $data = json_decode($json, true);

        $globalQuote = $data["Global Quote"] ?? null;

        if(empty($globalQuote)){
            return $this->json([]);
        }

        $responseData = [
            'symbol' => $symbol,
            "open" => $globalQuote['02. open'],
            "high" => $globalQuote['03. high'],
            "low" => $globalQuote['04. low'],
            "close" => $globalQuote['08. previous close'],
        ];

        $dispatcher->dispatch(
            new RequestCreatedEvent($this->getUser(), 'stock_quote', $responseData),
            RequestCreatedEvent::NAME
        );

        return $this->json($responseData);
    }
}
