<?php

namespace MamoPay\Api\Events;

class WebhookEvent
{
    public const CHARGE_FAILED = "charge.failed";
    public const CHARGE_SUCCEEDED = "charge.succeeded";
    public const CHARGE_REFUND_INITIATED = "charge.refund_initiated";
    public const CHARGE_REFUNDED = "charge.refunded";
    public const CHARGE_REFUND_FAILED = "charge.refund_failed";
    public const CHARGE_CARD_VERIFIED = "charge.card_verified";
    public const CHARGE_AUTHORIZED = "charge.authorized";
    public const SUBSCRIPTION_FAILED = "subscription.failed";
    public const SUBSCRIPTION_SUCCEEDED = "subscription.succeeded";
    public const PAYMENT_LINK_CREATE = "payment_link.create";
    public const PAYOUT_PROCESSED = "payout.processed";
    public const PAYOUT_FAILED = "payout.failed";

    public const ALL_EVENT_TYPES = [
        self::CHARGE_FAILED,
        self::CHARGE_SUCCEEDED,
        self::CHARGE_REFUND_INITIATED,
        self::CHARGE_REFUNDED,
        self::CHARGE_REFUND_FAILED,
        self::CHARGE_CARD_VERIFIED,
        self::CHARGE_AUTHORIZED,
        self::SUBSCRIPTION_FAILED,
        self::SUBSCRIPTION_SUCCEEDED,
        self::PAYMENT_LINK_CREATE,
        self::PAYOUT_PROCESSED,
        self::PAYOUT_FAILED,
    ];
}
