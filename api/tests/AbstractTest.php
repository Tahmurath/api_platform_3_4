<?php

// api/tests/AbstractTest.php
namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

abstract class AbstractTest extends ApiTestCase
{
    private ?string $token = null;

//    public function setUp(): void
//    {
//        self::bootKernel();
//    }
//
//    protected function createClientWithCredentials($token = null): Client
//    {
//        $token = $token ?: $this->getToken();
//
//        return static::createClient([], ['headers' => ['authorization' => 'Bearer '.$token]]);
//    }
//
//    protected function getToken($body = []): string
//    {
//        if ($this->token) {
//            return $this->token;
//        }
//
//        $response = static::createClient()->request('POST', '/login', ['json' => $body ?: [
//            'username' => 'admin@example.com',
//            'password' => '$3cr3t',
//        ]]);
//
//        $this->assertResponseIsSuccessful();
//        $data = $response->toArray();
//        $this->token = $data['token'];
//
//        return $data['token'];
//    }

    public function getRandomWord($len = 10) {
        $word = array_merge(range('a', 'z'), range('A', 'Z'));
        shuffle($word);
        return substr(implode($word), 0, $len);
    }
}
