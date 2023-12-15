<?php

namespace MamoPay\Api;


/**
 * HttpClient Class for interact with MamoPay API 
 * 
 */
class HttpClient
{
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_DELETE = 'DELETE';
    public const METHOD_PATCH = 'PATCH';

    protected int $timeoutInSeconds = 60;
    protected bool $waitOnRateLimit = false;
    protected int $sleepTimeOnRateLimitHitInSeconds = 20;


    protected string $apiKey;

    protected string $protocol = 'https';
    protected string $apiHost;
    protected string $apiLocation = '/manage_api';
    protected string $apiVersion = 'v1';
    protected string $userAgent = 'mamopay API Client (mamopay.com)';

    protected string $liveApiHost = 'business.mamopay.com';
    protected string $stagingApiHost = 'sandbox.business.mamopay.com';

    protected string $clientVersion = '1.0';

    protected bool $debug = false;
    protected bool $skipSslVerification = false;

    protected array $rawResponseHeaders = [];

    /**
     * MamoPay HttpClient
     * @param string $apikey
     * @param bool $is_testing
     */
    public function __construct($apikey, $is_testing = false)
    {
        $this->apiKey = $apikey;
        $this->apiHost = ($is_testing ? $this->stagingApiHost : $this->liveApiHost);
    }

    /**
     * @throws RateLimitException
     *
     * @return mixed
     */
    public function sendRequest($endpoint, $params = [], $method = self::METHOD_GET, $filters = [])
    {
        $endpoint = $this->getEndpoint($endpoint, $filters);

        $this->debug('URL: ' . $this->getUrl($endpoint));

        $curlSession = curl_init();

        curl_setopt($curlSession, CURLOPT_HEADER, false);
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curlSession, CURLOPT_URL, $this->getUrl($endpoint));
        curl_setopt($curlSession, CURLOPT_TIMEOUT, $this->timeoutInSeconds);

        curl_setopt($curlSession, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $this->apiKey, 'Content-Type: application/json', 'Accept: application/json']);
        curl_setopt($curlSession, CURLOPT_HEADERFUNCTION, function ($curl, $header) {
            $this->rawResponseHeaders[] = $header;

            return strlen($header);
        });

        $this->setPostData($curlSession, $method, $params);
        $this->setSslVerification($curlSession);

        $apiResult = curl_exec($curlSession);
        $headerInfo = curl_getinfo($curlSession);

        $this->debug('Raw result: ' . $apiResult);

        $apiResultJson = json_decode($apiResult);
        $apiResultHeaders = $this->parseRawHeaders();
        $this->resetRawHeaders();

        $result = new \stdClass();
        $result->status = false;

        // CURL failed
        if ($apiResult === false) {
            $result->error = true;
            $result['errorcode'] = 0;
            $result['errormessage'] = curl_error($curlSession);
            curl_close($curlSession);

            return $result;
        }

        curl_close($curlSession);

        if ($headerInfo['http_code'] == '429') {
            return $this->handleRateLimitReached($endpoint, $params, $method, $filters);
        }

        // API returns error
        if (! in_array($headerInfo['http_code'], ['200', '201', '204'])) {
            $result->error = true;
            $result->errorcode = $headerInfo['http_code'];
            $result->errormessage = json_decode($apiResult);

            return $result;
        }

        // API returns success
        $result->status = true;
        $result = (($apiResultJson === null) ? $apiResult : $apiResultJson);

        return $result;
    }

    /**
     * Enable debug mode gives verbose output on requests and responses
     */
    public function enableDebugmode(): void
    {
        $this->debug = true;
    }

    /**
     * Disable Curl's SSL verification for testing
     */
    public function disableSslVerification(): void
    {
        $this->skipSslVerification = true;
    }

    public function setApihost(string $apiHost): void
    {
        $this->apiHost = $apiHost;
    }

    /**
     * @param string $protocol http or https
     */
    public function setProtocol(string $protocol): void
    {
        $this->protocol = $protocol;
    }

    public function setUseragent(string $userAgent): void
    {
        $this->userAgent = $userAgent;
    }

    /**
     * Change the timeout for CURL requests
     */
    public function setTimeoutInSeconds(int $timeoutInSeconds): void
    {
        $this->timeoutInSeconds = $timeoutInSeconds;
    }

    public function enableRetryOnRateLimitHit(): void
    {
        $this->waitOnRateLimit = true;
    }

    protected function getUrl(string $endpoint): string
    {
        return $this->protocol . '://' . $this->apiHost . $this->apiLocation . '/' . $this->apiVersion . $endpoint;
    }

    protected function prepareData($params)
    {
        return json_encode($params);
    }

    protected function getEndpoint(string $endpoint, ?array $filters): string
    {
        if (! empty($filters)) {
            $i = 0;
            foreach ($filters as $key => $value) {
                if ($i == 0) {
                    $endpoint .= '?';
                } else {
                    $endpoint .= '&';
                }
                $endpoint .= $key . '=' . urlencode($value);
                $i++;
            }
        }

        return $endpoint;
    }

    protected function debug($message): void
    {
        if ($this->debug) {
            echo 'Debug: ' . $message . PHP_EOL;
        }
    }

    protected function parseRawHeaders(): array
    {
        $parsedHeaders = [];

        foreach ($this->rawResponseHeaders as $header) {
            $headerPieces = explode(':', $header, 2);

            if (! isset($headerPieces[0]) || ! isset($headerPieces[1])) {
                continue;
            }

            $parsedHeaders[strtolower($headerPieces[0])] = trim($headerPieces[1]);
        }

        return $parsedHeaders;
    }

    protected function resetRawHeaders(): void
    {
        $this->rawResponseHeaders = [];
    }

    protected function getRemainingRateLimit(array $apiResultHeaders)
    {
        return (array_key_exists('x-ratelimit-remaining', $apiResultHeaders)) ? $apiResultHeaders['x-ratelimit-remaining'] : null;
    }

    protected function setPostData($curlSession, $method, $params): void
    {
        if (! in_array($method, [self::METHOD_POST, self::METHOD_PUT, self::METHOD_DELETE])) {
            return;
        }

        $data = $this->prepareData($params);
        $this->debug('Data: ' . $data);

        curl_setopt($curlSession, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curlSession, CURLOPT_POSTFIELDS, $data);
    }

    protected function setSslVerification($curlSession): void
    {
        if ($this->skipSslVerification) {
            curl_setopt($curlSession, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curlSession, CURLOPT_SSL_VERIFYHOST, false);
        }
    }

    /**
     * @throws RateLimitException
     */
    protected function handleRateLimitReached($endpoint, $params = [], $method = self::METHOD_GET, $filters = [])
    {
        if (! $this->waitOnRateLimit) {
            // throw new RateLimitException('Rate limit exceeded. Try again later.');
        }

        $this->debug('Rate limit hit, sleeping and trying again');

        sleep($this->sleepTimeOnRateLimitHitInSeconds);

        return $this->sendRequest($endpoint, $params, $method, $filters);
    }
}
