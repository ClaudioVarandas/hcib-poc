<?php

namespace App\Tests\Funcional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testItCanRegisterUser(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/registration',[
            'name' => 'zed',
            'email' => 'zed@ggg.com',
            'password' => '123'
        ]);

        $this->assertResponseIsSuccessful();


        $response = $client->getResponse();
        $data = $response->getContent();

        $this->assertStringContainsString('{"message":"Registered Successfully"}', $data);
    }


    public function testItShouldReturnBadRequestIfEmailAlreadyExists(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/registration',[
            'name' => 'zed',
            'email' => 'zed@ggg.com',
            'password' => '123'
        ]);

        $this->assertResponseIsSuccessful();

        $response = $client->getResponse();
        $data = $response->getContent();

        $this->assertStringContainsString('{"message":"Registered Successfully"}', $data);

        $client->request('POST', '/api/registration',[
            'name' => 'zed',
            'email' => 'zed@ggg.com',
            'password' => '123'
        ]);
        $response = $client->getResponse();
        $data = $response->getContent();

        $this->assertResponseStatusCodeSame(400);

        $this->assertStringContainsString('{"message":"Account already exist."}', $data);
    }
}
