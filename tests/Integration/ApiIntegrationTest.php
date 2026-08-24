<?php

namespace MamoPay\Api\Tests\Integration;

use MamoPay\Api\Tests\TestCase;

/**
 * Live integration tests against the Mamo sandbox.
 *
 * Run with:
 *   MAMO_API_KEY=<sandbox-key> MAMO_SANDBOX=1 vendor/bin/phpunit --testsuite Integration
 */
class ApiIntegrationTest extends TestCase
{
    protected function setUp(): void
    {
        if (getenv('MAMO_RUN_INTEGRATION') !== '1') {
            $this->markTestSkipped('Integration tests disabled (set MAMO_RUN_INTEGRATION=1).');
        }
    }

    public function testFetchBusinessInfo()
    {
        $response = $this->client()->me();

        $this->assertFalse(isset($response->error));
        $this->assertNotEmpty($response->business_name);
    }

    public function testFetchAccountBalances()
    {
        $response = $this->client()->mybalance();

        $this->assertFalse(isset($response->error));
        $this->assertNotEmpty($response->wallets);
        $wallet = $response->wallets[0];
        $this->assertObjectHasProperty('currency', $wallet);
        $this->assertObjectHasProperty('balance', $wallet);
    }

    public function testPaymentLinkLifecycle()
    {
        $client = $this->client();
        $title = 'SDK Test Link ' . time();

        $link = $client->links()->create($title, 10, 'https://example.com/success');
        $this->assertFalse(isset($link->error), json_encode($link));
        $this->assertNotEmpty($link->id);
        $this->assertSame($title, $link->title);

        $fetched = $client->links()->get($link->id);
        $this->assertFalse(isset($fetched->error));
        $this->assertSame($link->id, $fetched->id);

        $updated = $client->links()->update($link->id, ['description' => 'updated by SDK test']);
        $this->assertFalse(isset($updated->error));

        $deleted = $client->links()->delete($link->id);
        $this->assertFalse(isset($deleted->error) && $deleted->error === true);
    }

    public function testFetchPayments()
    {
        $payments = $this->client()->transaction()->all(1, 5);
        $this->assertFalse(isset($payments->error));
        $this->assertObjectHasProperty('data', $payments);
    }

    public function testSubscriptionCreateAndCancel()
    {
        $client = $this->client();

        // Create a subscription attached to a payment link (sandbox only keeps
        // standalone subscription records that have been linked to a payment).
        $link = $client->links()->create('SDK sub-cancel test ' . time(), 10, 'https://example.com/success', [
            'subscription' => ['frequency' => 'monthly', 'frequency_interval' => 1, 'end_date' => '2026/12/31'],
        ]);
        $this->assertFalse(isset($link->error), json_encode($link));
        $this->assertNotEmpty($link->subscription->identifier ?? null);

        // Also verify the documented create-subscription endpoint itself works
        $subscription = $client->subscription()->create('monthly', 1, '', null, '', 1);
        $this->assertFalse(isset($subscription->error), json_encode($subscription));
        $this->assertNotEmpty($subscription->identifier);
        $this->assertSame(1, $subscription->monthly_start_date);

        $cancelled = $client->subscription()->cancelRecurring($link->subscription->identifier);
        $this->assertObjectHasProperty('success', $cancelled);
        $this->assertTrue($cancelled->success);

        $client->links()->delete($link->id);
    }

    public function testValidateIban()
    {
        $valid = $this->client()->recipient()->validateIban('AE070331234567890123456');
        $this->assertFalse(isset($valid->error));
        $this->assertTrue($valid->valid);
        $this->assertNotEmpty($valid->bank_name);

        $invalid = $this->client()->recipient()->validateIban('AE0700000000000000000000');
        $this->assertFalse(isset($invalid->error));
        $this->assertFalse($invalid->valid);
        $this->assertContains('bad_check_digits', $invalid->errors ?? []);
    }

    public function testWebhookLifecycle()
    {
        $client = $this->client();

        // Sandbox validates webhook reachability; point it at MAMO_WEBHOOK_URL (default httpbin).
        $webhook = $client->webhook()->create($this->webhookUrl(), ['charge.succeeded']);
        if (isset($webhook->error)) {
            $this->assertSame(422, $webhook->errorcode ?? null, json_encode($webhook));
            $this->markTestSkipped('Sandbox rejected webhook URL as unreachable: ' . json_encode($webhook->errormessage ?? ''));
        }
        $this->assertNotEmpty($webhook->id);

        $all = $client->webhook()->all();
        $this->assertFalse(isset($all->error));

        $deleted = $client->webhook()->delete($webhook->id);
        $this->assertTrue($deleted);
    }

    public function testPartnerCardsList()
    {
        // Partner cards are a gated feature; 403 simply means not provisioned for this key
        $cards = $this->client()->card()->listPartnerCards(1, 10);
        if (isset($cards->error) && ($cards->errorcode ?? null) == 403) {
            $this->addToAssertionCount(1); // endpoint reachable, feature not enabled for this key
            $this->markTestSkipped('Partner cards not enabled for this API key.');
        }
        $this->assertFalse(isset($cards->error));
    }

    public function testCardTransactionsList()
    {
        $txs = $this->client()->card()->transactions(1, 10);
        $this->assertFalse(isset($txs->error));
    }

    public function testExpenseReceiptsList()
    {
        $receipts = $this->client()->card()->expenseReceipts(1, 10);
        $this->assertFalse(isset($receipts->error));
    }
}
