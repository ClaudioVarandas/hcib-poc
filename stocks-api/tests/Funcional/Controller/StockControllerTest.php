<?php

namespace App\Tests\Funcional\Controller;

use App\Client\AlphaVantageClient;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StockControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
    }

    public function testItShouldReturnStockQuote(): void
    {
        $this->markTestSkipped();

        $body = '{"Global Quote":{"01. symbol":"XYZ","02. open":"2.4700","03. high":"2.5100","04. low":"2.3900","05. price":"2.4400","06. volume":"38891270","07. latest trading day":"2024-03-01","08. previous close":"2.4500","09. change":"-0.0100","10. change percent":"-0.4082%"}}';

        $container= static::getContainer();

        $alphaVantageClientMock = $this->getMockBuilder(AlphaVantageClient::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getGlobalQuote'])
            ->getMock();
        $alphaVantageClientMock
            ->expects(self::once())
            ->method('getGlobalQuote')
            ->willReturn(json_decode($body, true));

        $container->set(AlphaVantageClient::class, $alphaVantageClientMock);

        $this->createAuthenticatedClient('bob@ggg.com', '123456');
        $this->client->request('GET', '/api/stock?q=XYZ');

        $this->assertResponseIsSuccessful();

        $response = $this->client->getResponse();
        $data = $response->getContent();
    }


    protected function createAuthenticatedClient($username = 'user', $password = 'password'): void
    {
        $this->client->request(
            'POST',
            '/api/login_check',
            [],
            [],
            ['Content-Type' => 'application/json'],
            json_encode([
                'username' => $username,
                'password' => $password,
            ])
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));
    }

}
