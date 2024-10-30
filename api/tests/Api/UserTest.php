<?php

namespace App\tests\Api;

//use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\AbstractTest;

class UserTest extends AbstractTest
{

    public $_user;
    public function getUsers(): array
    {
        $user_name = ucfirst($this->getRandomWord(10));
        $email = $user_name.'@test.com';

        $this->_user = [
            'name' => $user_name,
            'email' => $email,
            'password' => $email,
            'plainPassword' => $email,
            'role' => 'ROLE_USER',
            'company' => '/companies/1',
        ];

        return $this->_user;
    }
    public function testCreateUser(): void
    {

        $user = $this->getUsers();

        static::createClient()->request('POST', '/users', [
            'json' => $user,
            'headers' => [
                'Content-Type' => 'application/ld+json',
            ],
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([

            'name' => $user['name'],
        ]);

        static::createClient()->request('GET', '/users?page=1', [
            'headers' => [
                'Content-Type' => 'application/ld+json',
            ],
        ]);
        $this->assertResponseStatusCodeSame(200);

        $this->assertJsonContains([
            '@context' => '/contexts/User',
            '@type' => 'hydra:Collection',
        ]);

        $response = static::createClient()->request('POST', '/auth', [
            'json' => [
                "email"=> $user['email'],
                "password"=> $user['password'],
            ],
            'headers' => [
                'Content-Type' => 'application/ld+json',
            ],
        ]);

        $this->assertResponseIsSuccessful();
        $data = $response->toArray();
        // check for $data['token'];
        $this->assertArrayHasKey('token', $data);

    }
}

