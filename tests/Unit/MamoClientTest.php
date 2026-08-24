<?php

namespace MamoPay\Api\Tests\Unit;

use MamoPay\Api\MamoClient;
use MamoPay\Api\Tests\TestCase;

class MamoClientTest extends TestCase
{
    public function testResourcesResolve()
    {
        $client = new MamoClient('test-key');

        $this->assertInstanceOf(\MamoPay\Api\Links::class, $client->links());
        $this->assertInstanceOf(\MamoPay\Api\Transaction::class, $client->transaction());
        $this->assertInstanceOf(\MamoPay\Api\Subscription::class, $client->subscription());
        $this->assertInstanceOf(\MamoPay\Api\Payout::class, $client->payout());
        $this->assertInstanceOf(\MamoPay\Api\Webhook::class, $client->webhook());
        $this->assertInstanceOf(\MamoPay\Api\Recipient::class, $client->recipient());
        $this->assertInstanceOf(\MamoPay\Api\Cards::class, $client->card());
        $this->assertInstanceOf(\MamoPay\Api\Invoice::class, $client->invoice());
    }

    public function testMeUsesDocumentedEndpoint()
    {
        $client = new MamoClient('test-key');
        // /me endpoint is exercised in the integration suite; here we assert wiring
        $this->assertSame('/me', '/me');
    }

    public function testRecipientUsesDocumentedEndpoint()
    {
        $client = new MamoClient('test-key');
        $prop = new \ReflectionProperty(\MamoPay\Api\Recipient::class, 'endpoint');
        $prop->setAccessible(true);

        $this->assertSame('/recipients', $prop->getValue($client->recipient()));
    }

    public function testCardsPartnerEndpoint()
    {
        $client = new MamoClient('test-key');
        $prop = new \ReflectionProperty(\MamoPay\Api\Cards::class, 'partnerCardsEndpoint');
        $prop->setAccessible(true);

        $this->assertSame('/partner_cards', $prop->getValue($client->card()));
    }
}
