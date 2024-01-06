# MamoPay

[![Latest Version on Packagist](https://img.shields.io/packagist/v/xplicit-dev/MamoPay.svg?style=flat-square)](https://packagist.org/packages/xplicit-dev/MamoPay)[![Total Downloads](https://img.shields.io/packagist/dt/xplicit-dev/MamoPay.svg?style=flat-square)](https://packagist.org/packages/xplicit-dev/MamoPay)

The MamoPay PHP library offers seamless integration with the MamoPay API for PHP-based applications, streamlining access and enhancing functionality.

## Requirements

A minimum of PHP 5.6.0 up to 8.1

## Installation

You can install the package via Composer:

```bash
composer require xplicit-dev/mamopay
```

# Getting Started

Obtain your API Key:

Log in to the MamoPay dashboard ([https://dashboard.mamopay.com/manage/developer](https://dashboard.mamopay.com/manage/developer))

and navigate to the Developer section to get your API key.

Instantiate MamoPay Client:

```php
use MamoPay\Api\MamoClient;


$client = (new MamoClient('API_KEY'));
```

For sandbox testing, omit the API key:

```php
$client = (new MamoClient());
```

The resources can be accessed via the `$client` object. All the methods invocations follow the following pattern

```php
// $client->class()->function() to access the API

//Example

$client->links()->get($linkId);
```

# Supported Resources

- [Links](#links-section)
  
- [Transaction](#transaction-section)
  
- [Subscription](#subscription-section)
  
- [Payout](#payout-section)
  
- [Webhook](#webhook-section)
  

## Links <a name="links-section"></a>

The resource to generate vanilla and subscription payment links

- generate vanilla payment link:

see params here https://mamopay.readme.io/reference/post_links

```php
$params = ['is_widget' => true , 'save_card'=>true];

$response = $client->links()->create($title,$amount,$returnUrl,$params);
```

this will return a \MamoPay\Api\Objects\PaymentLink object

refer: https://mamopay.readme.io/reference/payment-link-object

```php
$id = $response->id;

$payment_url = $response->payment_url;
```

- Fetching all Payment Links:

```php
$client->links()->all();
```

- Update Payment Link:

```php
$client->links()->update($linkID,$params);
```

- Delete Payment Link:

```php
$client->links()->delete($linkID);
```

- Fetch Payment Link Info:

```php
$client->links()->get($linkID);
```

## Transaction <a name="transaction-section"></a>

Initiate transactions by merchant (Merchant Initiated Transaction)

Merchant Initiated Transactions (MIT) allows a business to use card details, that were stored during previous transactions, to charge their customers.

```php
$charge = $client->transaction()->create($card_id,$amount);
```

this will return a \MamoPay\Api\Objects\TransactionInfo object

refer : https://mamopay.readme.io/reference/charge-object

```php
$chargeID = $charge->id;
```

- Fetch Transaction Info

```php
$client->transaction()->get($chargeID);
```

- Fetch all Transactions

```php
$client->transaction()->all();
```

- Refund Payment

```php
$client->transaction()->refund($chargeId,$amount);
```

## Subscription <a name="subscription-section"></a>

- Fetches all subscribers of subscription.

```php
$client->subscription()->all($subscriptionId);
```

- Fetches all subscription payments made against a Recurring Payment item.

```php
$client->subscription()->get($subscriptionId);
```

- Unsubscribe subscription

```php
$client->subscription()->unSubscribe($subscriptionId,$subscriberId);
```

- Cancels an existing recurring payment. This is NOT to unsubscribe a customer from a recurring payment that they have subscribed to. This deletes a previously created subscription for a business.

```php
$client->subscription()->cancelRecurring($subscriptionId);
```

## Payout <a name="payout-section"></a>

- Fetch all Disbursements

```php
$client->payout()->all();
```

Issue Disbursements

- Allows the issuance of disbursement

```php
$client->payout()->issue($account_no, $amount, $first_name, $last_name = '', $reason = '', $transfer_method = 'BANK_ACCOUNT');
```

- Allows the issuance of disbursements in bulk

```php
<?php
use MamoPay\Api\Objects\Disbursement;

$client = (new MamoClient());

$disbursement[0] = (new Disbursement())->set([
'account' => 'AE080200000123223333121',
'amount' => 10,
'first_name_or_business_name' => 'John',
'last_name' => 'Doe',
]);

$disbursement[1] = (new Disbursement())->set([
'account' => 'AE080200000123223333121',
'amount' => 20.5,
'first_name_or_business_name' => 'John',
'last_name' => 'Doe',
'reason' => 'refund for lorem ipsum',
]);

$disbursements = $client->payout()->issueMultiple($disbursement);
```

## Webhook <a name="webhook-section"></a>

-Webhook registration for updates on one-off payment statuses and subscription payment statuses.

```php
$client->webhook()->create($uri,$events,'authentication header');
```

this will return \MamoPay\Api\Objects\WebhookInfo object

- WebhookEvent class contain all event constants

```php
use MamoPay\Api\Events\WebhookEvent;



$response = $client->webhook()->create("http://example.com",WebhookEvent::ALL_EVENT_TYPES,'authentication header');

$response = $client->webhook()->create("http://example.com",[WebhookEvent::CHARGE_CARD_VERIFIED,WebhookEvent::CHARGE_SUCCEEDED]);

$webhookId = $response->id;
```

- Fetches all registered webhooks for a given business

```php
$client->webhook()->all();
```

- update webhook details

```php
$client->webhook()->update($webhookId,"http://example.com",WebhookEvent::ALL_EVENT_TYPES,'authentication header');
```

- Delete a registered webhook

```php
$client->webhook()->delete($webhookId);
```

<!-- ### Changelog
Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.
## Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details. -->

### Security

If you discover any security related issues, please email blackboy.email@gmail.com instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.