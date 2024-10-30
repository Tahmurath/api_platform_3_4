<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class CompanyTest extends ApiTestCase
{
    public function testCreateCompany(): void
    {
        static::createClient()->request('POST', '/companies', [
            'json' => [
                'name' => 'Companasdassdfs',
            ],
            'headers' => [
                'Content-Type' => 'application/ld+json',
            ],
        ]);

        //$this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            '@context' => '/contexts/Company',
            '@type' => 'Company',
            'name' => 'Compasdanssdfs',
        ]);
    }
}

