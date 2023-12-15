<?php

namespace MamoPay\Api\Objects;

/**
 * This object represents MamoPay Webhook info.
 */
class WebhookInfo
{
    /** @var string Identifier */
    public string $id;

    /** @var string URL */
    public string $url;

    /** @var array Enabled events */
    public array $enabled_events;

    /** @var string Authentication header */
    public string $auth_header;
}
