<?php

namespace App\Client;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AlphaVantageClient
{
    private const BASE_URI = "https://www.alphavantage.co";
    private string|null $apiKey;

    public function __construct(
        ParameterBagInterface $params,
        private readonly HttpClientInterface $client
    )
    {
        $this->apiKey = $params->get('alpha_vantage_api_key');
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getGlobalQuote(string $symbol): array
    {
        $url = sprintf("%s/query?function=GLOBAL_QUOTE&symbol=%s&apikey=%s",
            self::BASE_URI,
            $symbol,
            $this->apiKey
        );

        $response = $this->client->request('GET', $url);

        return $response->toArray();
    }
}