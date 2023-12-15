<?php

namespace MamoPay\Api;

use MamoPay\Api\Objects\WebhookInfo;

/**
 * Webhook
 */
class Webhook
{
    private HttpClient $httpClient;

    private $endpoint = '/webhooks/';

    public function __construct($httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Webhook registration for updates on one-off payment statuses and subscription payment statues
     *
     * @param string $uri
     * @param array $enabled_events
     * @param string $auth_header
     * @return WebhookInfo
     */
    public function create(string $uri, array $enabled_events, string $auth_header)
    {
        $params = ['uri' => $uri, 'enabled_events' => $enabled_events, 'auth_header' => $auth_header];
        return $this->httpClient->sendRequest($this->endpoint, $params, $this->httpClient::METHOD_POST);
    }

    /**
     * Fetches all registered webhooks for a given business
     *
     * @return array<WebhookInfo>
     */
    public function all()
    {
        return $this->httpClient->sendRequest($this->endpoint, [], $this->httpClient::METHOD_GET);
    }

    /**
     * To update webhook details
     *
     * @param string $webhookId
     * @param string $uri
     * @param array $enabled_events
     * @param string $auth_header
     * @return WebhookInfo
     */
    public function update(string $webhookId, string $uri, array $enabled_events, string $auth_header)
    {
        $params = ['uri' => $uri, 'enabled_events' => $enabled_events, 'auth_header' => $auth_header];
        return $this->httpClient->sendRequest($this->endpoint, $params, $this->httpClient::METHOD_PATCH);
    }

    /**
     * To delete a registered webhook
     *
     * @param string $webhookId
     *
     * @return bool
     */
    public function delete(string $webhookId)
    {
        $status = false;
        $response = $this->httpClient->sendRequest($this->endpoint, [], $this->httpClient::METHOD_DELETE);
        if (!empty($response['success']) && $response['success'] == true) {
            $status = true;
        }
        return $status;
    }

}
