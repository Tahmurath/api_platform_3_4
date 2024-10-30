<?php

namespace App\Tests\Api;

//use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\AbstractTest;

class CompanyTest extends AbstractTest
{
    public function testCreateCompany(): void
    {

        $company_name = $this->getRandomWord(10);

        static::createClient()->request('POST', '/companies', [
            'json' => [
                'name' => $company_name,
            ],
            'headers' => [
                'Content-Type' => 'application/ld+json',
            ],
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            '@context' => '/contexts/Company',
            '@type' => 'Company',
            'name' => $company_name,
        ]);
    }



}

