# MamoPay

[![Latest Version on Packagist](https://img.shields.io/packagist/v/xplicit-dev/MamoPay.svg?style=flat-square)](https://packagist.org/packages/xplicit-dev/MamoPay) [![Total Downloads](https://img.shields.io/packagist/dt/xplicit-dev/MamoPay.svg?style=flat-square)](https://packagist.org/packages/xplicit-dev/MamoPay)

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

For sandbox testing, pass 'true' as second parameter:

```php
$client = (new MamoClient('API_KEY',true));
```

The resources can be accessed via the `$client` object. All the methods invocations follow the following pattern

```php
// $client->class()->function() to access the API

//Example

$client->links()->get($linkId);
```

- Fetch Business Info:

```php
$client->me();
```

- Fetch Account Balances:

```php
$client->mybalance();
```

# Supported Resources

- [Links](#links-section)
  - [Transaction](#transaction-section)
  - [Subscription](#subscription-section)
  - [Payout](#payout-section)
  - [Webhook](#webhook-section)
- [Recipient](#recipient-section)
- [Card](#card-section)
- [Invoice](#invoice-section)

### Use of Unlisted Resources

The MamoClient SDK allows you to utilize resources not listed within this package. This flexibility enables you to leverage any additional resources provided by the MamoPay API without constraints.

```php
use MamoPay\Api\MamoClient;

$client = (new MamoClient('API_KEY'));
$params = ['card_id' => $card_id, 'amount' => $amount, 'currency' => $currency];
$response = $client->httpClient->sendRequest('end_point',$params,HttpClient::METHOD_POST);
```

## Links <a name="links-section"></a>

The resource to generate vanilla and subscription payment links

- generate vanilla payment link:

see params here https://mamopay.readme.io/reference/post_links

```php
$params = ['is_widget' => true , 'save_card'=>true];

$response = $client->links()->create($title,$amount,$returnUrl,$params);

// subscription payment link (recommended): create the subscription first, then attach it
$subscription = $client->subscription()->create('monthly', 1);
$params = ['link_type' => 'inline', 'subscription_id' => $subscription->identifier];
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
$client->transaction()->all($page, $perPage);
```

- Fetch Transaction Info (includes `refunds[]` breakdown and `max_refund_amount` on the returned TransactionInfo object)

```php
$client->transaction()->get($chargeID);
```

- Capture Payment - to capture an "On hold" payment

```php
$client->transaction()->capture($chargeId,$amount);
```

- Reverse Payment - to reverse an "On hold" payment

```php
$client->transaction()->reverse($chargeId);
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

- Create Subscription
Creates a subscription object that defines a billing schedule (frequency, interval, start/end date, etc). This only creates the subscription object; attach the returned subscription identifier to a payment link using the `subscription_id` attribute on the Links create/update APIs.

```php
$response = $client->subscription()->create($frequency,$frequency_interval,$end_date,$payment_quantity,$weekly_start_day,$monthly_start_date);

// frequency: 'daily', 'weekly', 'monthly', 'annually', 'test' (daily and test are sandbox-only)
// e.g. monthly subscription starting on the 5th:
$response = $client->subscription()->create('monthly', 1, '', null, '', 5);

$subscriptionId = $response->id;
```

- Change Customer Subscription
Upgrades a subscriber from their current subscription to a target subscription without re-entering card details. Prorates and charges the difference against the target plan's payment link.

```php
$client->subscription()->changeCustomerSubscription($subscriptionId,$subscriberId,$targetSubscriptionId);
```

## Payout <a name="payout-section"></a>

- Fetch all Disbursements

```php
$client->payout()->all();

// with pagination (documented query params page / per_page)
$client->payout()->all($page, $perPage);
```

Issue Disbursements

- Allows the issuance of disbursement

```php
$client->payout()->issue($account_no, $amount, $first_name, $last_name = '', $reason = '', $transfer_method = 'BANK_ACCOUNT');

// pay out to a saved recipient instead of raw bank details (optional trailing params)
$client->payout()->issue(null, $amount, '', '', $reason, 'BANK_ACCOUNT', $recipient_id);
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

// payouts to saved recipients can also be issued in bulk via the recipient_id property
$disbursement[2] = (new Disbursement())->set([
'recipient_id' => 'REP-6BB7CA8DC7',
'amount' => 15,
'reason' => 'payout to saved recipient',
]);

$disbursements = $client->payout()->issueMultiple($disbursement);
```

- Create International Payout
Initiates an international payout to an existing recipient (created via the Create Recipient API). The destination currency and corridor are determined by the recipient's bank details. Exactly one of `$source_amount` or `$destination_amount` must be provided - not both.

```php
$response = $client->payout()->createInternational($recipient_id,$reason,$source_amount,null,'description');

// an Idempotency-Key header is always sent; pass your own as the last argument to control retries
$response = $client->payout()->createInternational($recipient_id,$reason,$source_amount,null,'description','my-idempotency-key');
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

- Delete all registered webhooks

```php
$client->webhook()->deleteAll();
```

## Recipient-section <a name="recipient-section"></a>

- Fetches all Recipients.

```php
$client->recipient()->all();
```

- Create Recipient
Allows a user to create recipient.

```php
$recipient = (new RecipientInfo())->set([
    'recipient_type' => RecipientInfo::RECIPIENT_TYPE_INDIVIDUAL,
    'first_name' => 'John',
    'last_name' => 'Doe',
    'email' => 'john.doe@example.com',
    'relationship' => RecipientInfo::RELATIONSHIP_CUSTOMER,
    'reason' => 'Payment for services',
    'eid_number' => '784-XXXX-XXXXXXX-0',
    'address' => (new Address())->set([
        'address_line1' => '123 Main Street',
        'address_line2' => 'Apt 4B',
        'city' => 'Dubai',
        'state' => 'AE',
        'country' => 'AE'
    ]),
    'bank' => (new Bank())->set([
        'iban' => 'AE080200000123223333121',
        'account_number' => '123223333121',
        'name' => 'ABC Bank',
        'bic_code' => 'ABCDUAE123',
        'address' => 'XYZ Bank Tower, Dubai',
        'country' => 'AE'
    ])
]);

$client->recipient()->create($recipient)

this will return a \MamoPay\Api\Objects\RecipientInfo object
```

- Update Recipient
Allows a user to update recipient details.


```php
$recipient = (new RecipientInfo())->set([
    'recipient_type' => RecipientInfo::RECIPIENT_TYPE_INDIVIDUAL,
    'first_name' => 'John',
    'last_name' => 'Doe',
    'email' => 'john.doe@example.com',
    'relationship' => RecipientInfo::RELATIONSHIP_CUSTOMER,
    'reason' => 'Payment for services',
    'eid_number' => '784-XXXX-XXXXXXX-0',
    'address' => (new Address())->set([
        'address_line1' => '123 Main Street',
        'address_line2' => 'Apt 4B',
        'city' => 'Dubai',
        'state' => 'AE',
        'country' => 'AE'
    ]),
    'bank' => (new Bank())->set([
        'iban' => 'AE080200000123223333121',
        'account_number' => '123223333121',
        'name' => 'ABC Bank',
        'bic_code' => 'ABCDUAE123',
        'address' => 'XYZ Bank Tower, Dubai',
        'country' => 'AE'
    ])
]);

$client->recipient()->update($recipientID,$recipient);

this will return a \MamoPay\Api\Objects\RecipientInfo object

recipientID is the recipient identifier returned when creating a recipient.

```

- Fetch Recipient
Allows a user to fetch recipient details.

```php
$client->recipient()->get($recipientID)

```

- Delete Recipient
Allows a user to delete recipient.

```php
$client->recipient()->delete($recipientID);
```

- Fetch Recipient Balance
Fetches the real-time balance of a recipient's ledger. Recipients accumulate balance from payouts_share splits on payments, and the balance is deducted when a payout is issued to the recipient.

```php
$client->recipient()->balance($recipientID);
```

- Validate IBAN
Validates a UAE IBAN and returns the bank it belongs to. Always returns 200 OK for a well-formed request - an invalid IBAN is reflected by `valid: false` in the response with an `errors` array, not by an error response.

```php
$response = $client->recipient()->validateIban('AE070331234567890123456');

// $response->valid, $response->bank_name, $response->bic_code (or $response->errors when invalid)
```


## Card Section <a name="card-section"></a>
Create Virtual Corporate Card
A Virtual Corporate Card (VCC) is a digital payment solution for businesses to simplify corporate expenses like travel and accommodations.

Parms : 

amount
The amount on the VCC card. The value can not exceed the card balance.

email
Cardholder’s email address. Card holder must be completed KYC.

booking_id
Booking reference in case the card will be used for a 1 time booking.

verification_email
The email address that will be used for verification purposes.

```php
 $client->card()->create(float $amount, string $email, string $booking_id='', string $verification_email = '', array $params = []);
 ```

Partner Cards
These APIs allow you to issue and manage virtual partner cards with specific limits and controls for your business partners. Available upon request for a tailored integration.

- Create Partner Card

```php
$response = $client->card()->createPartnerCard($amount,$email,$booking_id,$verification_email,$transactions_limit);
```

- Get Partner Cards List (paginated)

```php
$client->card()->listPartnerCards($page, $perPage);
```

- Get Partner Card Details

```php
$client->card()->getPartnerCard($identifier);
```

- Update Partner Card (update the amount limit of an active card)

```php
$client->card()->updatePartnerCard($identifier,$amount);
```

- Cancel Partner Card (only active cards can be cancelled)

```php
$client->card()->cancelPartnerCard($identifier);
```

- Get Partner Card Transactions (paginated)

```php
$client->card()->listPartnerCardTransactions($identifier,$page, $perPage);
```

- Get Partner Card Transaction Details

```php
$client->card()->getPartnerCardTransaction($identifier,$transaction_identifier);
```

Card Transactions & Expenses

- Fetching Card Transactions (all transactions for cards, paginated)

```php
$client->card()->transactions($page, $perPage);
```

- Fetch Card Transaction Details

```php
$client->card()->getTransaction($transactionId);
```

- Update Expense (description, invoice number, status: incomplete / pending_review / ready / synced)

```php
$client->card()->updateExpense($expenseId,$invoice_number,$description,$status);
```

- Fetching Expense Receipts (paginated)

```php
$client->card()->expenseReceipts($page, $perPage);
```

- Fetch Expense Receipt Details

```php
$client->card()->getExpenseReceipt($identifier);
```

## Invoice <a name="invoice-section"></a>

Create Invoice - API to create and send an invoice to a customer via email.

```php
$response = $client->invoice()->create(
    amount: 100.00,
    email: 'customer@example.com',
    amount_currency: 'AED',   // AED, USD, EUR, GBP or SAR
    description: 'Consulting services',
);

// additional documented options are available as optional params:
// customer_type, first_name, last_name, phone_number, vat_enabled,
// plus via $params: additional_heading, additional_details, additional_cc_emails,
// include_external_id, external_id, processing_fee_percentage, processing_fee_amount

$invoiceId = $response->id;
```




<!-- ### Changelog
Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.
## Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details. -->

## Testing

Unit tests run offline (no API key required):

```bash
composer install
vendor/bin/phpunit --testsuite Unit
```

Integration tests hit the live Mamo sandbox. Provide your sandbox API key via environment variables:

```bash
export MAMO_API_KEY="your-sandbox-key"
export MAMO_SANDBOX=true            # false for production keys
export MAMO_RUN_INTEGRATION=1       # integration tests skip without this
export MAMO_WEBHOOK_URL="https://your-public-endpoint/hook"   # optional; must be publicly reachable

vendor/bin/phpunit --testsuite Integration
```

Run everything with `vendor/bin/phpunit`. The webhook test uses `MAMO_WEBHOOK_URL` (defaults to https://httpbin.org/post) — point it at a reachable endpoint (e.g. an ngrok tunnel) so the sandbox's reachability validation passes.

### Security

If you discover any security related issues, please email anaspk144@gmail.com instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
