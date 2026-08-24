<?php

namespace MamoPay\Api\Tests\Unit;

use MamoPay\Api\MamoClient;
use MamoPay\Api\Objects\Disbursement;
use MamoPay\Api\Objects\Expense;
use MamoPay\Api\Objects\IbanValidation;
use MamoPay\Api\Objects\InternationalPayout;
use MamoPay\Api\Objects\CardTransaction;
use MamoPay\Api\Objects\PaginatedList;
use MamoPay\Api\Objects\PaymentLink;
use MamoPay\Api\Objects\PaymentMethod;
use MamoPay\Api\Objects\RecipientBalance;
use MamoPay\Api\Objects\Subscription as SubscriptionObject;
use MamoPay\Api\Objects\TransactionInfo;
use MamoPay\Api\Tests\TestCase;

class ObjectsTest extends TestCase
{
    public function testPaymentLinkNewDocumentedProperties()
    {
        $link = new PaymentLink();
        $link->set([
            'subscription_id' => 'MPB-SUB-123',
            'link_type' => 'inline',
            'terms_and_conditions_url' => 'https://example.com/tac',
            'internal_note' => 'note',
            'expiration_date' => '2026/12/31',
            'lang' => 'en',
            'max_amount' => 500.0,
            'processing_fee_amount' => 2.5,
        ]);

        $this->assertSame('MPB-SUB-123', $link->subscription_id);
        $this->assertSame('inline', $link->link_type);
        $this->assertSame('en', $link->lang);
        $this->assertSame(500.0, $link->max_amount);
    }

    public function testTransactionInfoRefundFields()
    {
        $info = new TransactionInfo();
        $refunds = [
            (object) ['id' => 'REFUND-A', 'amount' => 2, 'amount_currency' => 'AED'],
            (object) ['id' => 'REFUND-B', 'amount' => 8, 'amount_currency' => 'AED'],
        ];
        $info->set(['refunds' => $refunds, 'max_refund_amount' => 0.0]);

        $this->assertCount(2, $info->refunds);
        $this->assertSame('REFUND-A', $info->refunds[0]->id);
        $this->assertSame(0.0, $info->max_refund_amount);
    }

    public function testPaymentMethodCardFields()
    {
        $pm = new PaymentMethod();
        $pm->set([
            'type' => 'CREDIT VISA',
            'card_id' => 'CARD-5DF50209F8',
            'card_expiry_month' => '12',
            'card_expiry_year' => '2028',
        ]);

        $this->assertSame('CARD-5DF50209F8', $pm->card_id);
        $this->assertSame('12', $pm->card_expiry_month);
        $this->assertSame('2028', $pm->card_expiry_year);
    }

    public function testSubscriptionObjectStartDayFields()
    {
        $sub = new SubscriptionObject();
        $sub->set(['monthly_start_date' => 5, 'weekly_start_day' => 'monday']);

        $this->assertSame(5, $sub->monthly_start_date);
        $this->assertSame('monday', $sub->weekly_start_day);
    }

    public function testAddressProvinceAndZip()
    {
        $address = new \MamoPay\Api\Objects\Address();
        $address->set(['address_line1' => '1 Main St', 'city' => 'Dubai', 'zip' => '00000', 'country' => 'AE']);

        $this->assertSame('00000', $address->zip);
        $this->assertNull($address->province);
    }

    public function testDisbursementRecipientId()
    {
        $d = new Disbursement();
        $d->set(['recipient_id' => 'REP-6BB7CA8DC7', 'amount' => '100', 'reason' => 'test']);

        $this->assertSame('REP-6BB7CA8DC7', $d->recipient_id);
        $json = json_encode($d);
        $this->assertStringContainsString('"recipient_id":"REP-6BB7CA8DC7"', $json);
        $this->assertStringContainsString('"transfer_method":"BANK_ACCOUNT"', $json);
    }

    public function testNewResponseObjectsHydrate()
    {
        $expense = new Expense();
        $expense->set(['id' => 'EXP-1', 'status' => 'ready', 'invoiceNumber' => '123']);

        $tx = new CardTransaction();
        $tx->set(['id' => 'TX-1', 'amount' => 10.5, 'card_last4' => '4242']);

        $list = new PaginatedList();
        $list->set(['data' => [], 'pagination_meta' => new \MamoPay\Api\Objects\Pagination()]);

        $balance = new RecipientBalance();
        $balance->set(['identifier' => 'LEDGER-1', 'balance' => 99.5, 'currency' => 'AED']);

        $iban = new IbanValidation();
        $iban->set(['iban' => 'AE070331234567890123456', 'valid' => false, 'errors' => ['bad_check_digits']]);

        $payout = new InternationalPayout();
        $payout->set(['id' => 'PAYOUT-1', 'status' => 'processing', 'currency' => 'INR']);

        $this->assertSame('ready', $expense->status);
        $this->assertSame('123', $expense->invoiceNumber);
        $this->assertSame('TX-1', $tx->id);
        $this->assertInstanceOf(\MamoPay\Api\Objects\Pagination::class, $list->pagination_meta);
        $this->assertSame(99.5, $balance->balance);
        $this->assertFalse($iban->valid);
        $this->assertSame(['bad_check_digits'], $iban->errors);
        $this->assertNull($iban->bank_name);
        $this->assertSame('PAYOUT-1', $payout->id);
    }

    public function testBackwardCompatibleSignaturesUnchanged()
    {
        // Links::create keeps its original parameter order
        $r = new \ReflectionMethod(\MamoPay\Api\Links::class, 'create');
        $names = array_map(fn ($p) => $p->getName(), $r->getParameters());
        $this->assertSame(['title', 'amount', 'returnUrl', 'params'], $names);

        // Payout::issue original first params unchanged, new ones appended
        $r = new \ReflectionMethod(\MamoPay\Api\Payout::class, 'issue');
        $params = $r->getParameters();
        $this->assertSame('account_no', $params[0]->getName());
        $this->assertSame('amount', $params[1]->getName());
        $this->assertSame('first_name', $params[2]->getName());
        $this->assertTrue($params[6]->isOptional());   // recipient_id
        $this->assertTrue($params[7]->isOptional());   // params
        $this->assertEquals(3, $r->getNumberOfRequiredParameters());

        // Recipient::update still accepts a RecipientInfo object (union widened)
        $r = new \ReflectionMethod(\MamoPay\Api\Recipient::class, 'update');
        $this->assertFalse($r->getParameters()[1]->hasType() && $r->getParameters()[1]->getType()->isBuiltin());

        // Webhook::create signature untouched
        $r = new \ReflectionMethod(\MamoPay\Api\Webhook::class, 'create');
        $names = array_map(fn ($p) => $p->getName(), $r->getParameters());
        $this->assertSame(['uri', 'enabled_events', 'auth_header'], $names);
    }
}
