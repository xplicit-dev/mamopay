<?php

namespace MamoPay\Api\Tests\Unit;

use MamoPay\Api\HttpClient;
use MamoPay\Api\Tests\TestCase;

class HttpClientTest extends TestCase
{
    public function testEndpointBuildsWithoutFilters()
    {
        $client = $this->offlineClient();
        $method = new \ReflectionMethod(HttpClient::class, 'getEndpoint');
        $method->setAccessible(true);

        $this->assertSame('/links/', $method->invoke($client, '/links/', []));
    }

    public function testEndpointApppliesFiltersAndSkipsNulls()
    {
        $client = $this->offlineClient();
        $method = new \ReflectionMethod(HttpClient::class, 'getEndpoint');
        $method->setAccessible(true);

        $endpoint = $method->invoke($client, '/payouts/', ['page' => 2, 'per_page' => null]);

        $this->assertSame('/payouts/?page=2', $endpoint);
    }

    public function testUrlConstruction()
    {
        $client = $this->offlineClient();
        $method = new \ReflectionMethod(HttpClient::class, 'getUrl');
        $method->setAccessible(true);

        $url = $method->invoke($client, '/me');

        $this->assertSame('https://business.mamopay.com/manage_api/v1/me', $url);
    }

    public function testUrlConstructionForSandbox()
    {
        $client = new HttpClient('test-key', true);
        $method = new \ReflectionMethod(HttpClient::class, 'getUrl');
        $method->setAccessible(true);

        $url = $method->invoke($client, '/finances');

        $this->assertSame('https://sandbox.dev.business.mamopay.com/manage_api/v1/finances', $url);
    }

    public function testSendRequestAcceptsOptionalHeadersArgument()
    {
        $ref = new \ReflectionMethod(HttpClient::class, 'sendRequest');

        $params = $ref->getParameters();
        $this->assertCount(5, $params);
        $this->assertSame('headers', $params[4]->getName());
        $this->assertTrue($params[4]->isDefaultValueAvailable());
        $this->assertSame([], $params[4]->getDefaultValue());
    }
}
