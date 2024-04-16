<?php

namespace App\Tests\Unit;

use App\Client\AlphaVantageClient;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class AlphaVantageClientTest extends KernelTestCase
{

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testItDoCenas()
    {
        $paramBag = static::getContainer()->getParameterBag();

        $body = '{"Global Quote":{"01. symbol":"aaaaaaaaaaa","02. open":"2.4700","03. high":"2.5100","04. low":"2.3900","05. price":"2.4400","06. volume":"38891270","07. latest trading day":"2024-03-01","08. previous close":"2.4500","09. change":"-0.0100","10. change percent":"-0.4082%"}}';
        $httpClientMock = new MockHttpClient(
            new MockResponse($body, [
                    'http_code' => 200,
                    'response_headers' => ['Content-Type: application/json'],
                ]
            ),
            'https://example.com'
        );

        $sut = new AlphaVantageClient($paramBag, $httpClientMock);
        $result = $sut->getGlobalQuote('ZZZZ');

        $this->assertSame($result, json_decode($body, true));
    }
}