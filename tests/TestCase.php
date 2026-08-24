<?php

namespace MamoPay\Api\Tests;

use MamoPay\Api\HttpClient;
use MamoPay\Api\MamoClient;

/**
 * Base test case providing a shared client factory.
 *
 * The API key is read from the MAMO_API_KEY environment variable.
 * Integration tests only run when MAMO_RUN_INTEGRATION=1.
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function apiKey(): string
    {
        $key = getenv('MAMO_API_KEY');
        if ($key === false || $key === '') {
            $this->markTestSkipped('MAMO_API_KEY environment variable not set.');
        }

        return $key;
    }

    protected function useSandbox(): bool
    {
        return getenv('MAMO_SANDBOX') !== 'false';
    }

    /**
     * Webhook URL used by webhook integration tests.
     * Override with the MAMO_WEBHOOK_URL environment variable - it must be
     * publicly reachable, as the sandbox validates reachability on registration.
     */
    protected function webhookUrl(): string
    {
        $url = getenv('MAMO_WEBHOOK_URL');
        if ($url === false || $url === '') {
            $url = 'https://httpbin.org/post';
        }

        return $url;
    }

    protected function client(): MamoClient
    {
        return new MamoClient($this->apiKey(), $this->useSandbox());
    }

    /**
     * Build an HttpClient whose responses are served from a local test double
     * by pointing it at a stub host (used for offline unit tests of URL/verb
     * construction via reflection instead of network).
     */
    protected function offlineClient(): HttpClient
    {
        return new HttpClient('test-key', false);
    }
}
