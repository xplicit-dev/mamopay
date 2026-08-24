<?php

namespace MamoPay\Api;

/**
 * This class represents the mamopay Virtual Corporate Card
 */
class Cards
{
    private HttpClient $httpClient;

    private $endpoint = '/vcc_cards';

    private $partnerCardsEndpoint = '/partner_cards';

    public function __construct($httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Create Virtual Corporate Card
     *
     * A Virtual Corporate Card (VCC) is a digital payment solution for businesses to simplify corporate expenses like travel and accommodations.
     *
     * @param float $amount The amount on the VCC card. The value can not exceed the card balance.
     * @param string $email Cardholder’s email address. Card holder must be completed KYC.
     * @param string $booking_id Booking reference in case the card will be used for a 1 time booking.
     * @param string $verification_email The email address that will be used for verification purposes.
     * @param array $params
     *
     * @return object{
     *     url: string,
     * }
     *
     */
    public function create(float $amount, string $email, string $booking_id='', string $verification_email = '', array $params = [])
    {
        $params = array_merge(['amount' => $amount, 'email' => $email, 'booking_id' => $booking_id, 'verification_email' => $verification_email], $params);

        return $this->httpClient->sendRequest($this->endpoint, $params, $this->httpClient::METHOD_POST);
    }

    /**
     * Create Partner Card
     * Create a partner card for business transactions. This API allows you to issue virtual cards with specific
     * limits and controls for your business partners. This API is available upon request for a tailored integration.
     *
     * @param float $amount The amount limit for the partner card
     * @param string $email Cardholder's email address. Card holder must have completed KYC
     * @param string $booking_id Optional. Booking reference in case the card will be used for a 1 time booking
     * @param string $verification_email Optional. The email address used for verification purposes
     * @param int|null $transactions_limit Optional. Maximum number of transactions allowed on the card
     * @param array $params Additional parameters
     * @return object
     */
    public function createPartnerCard(float $amount, string $email, string $booking_id = '', string $verification_email = '', int $transactions_limit = null, array $params = [])
    {
        $params = array_merge([
            'amount' => $amount,
            'email' => $email,
            'booking_id' => $booking_id,
            'verification_email' => $verification_email,
            'transactions_limit' => $transactions_limit,
        ], $params);
        $params = array_filter($params, function ($value) {
            return $value !== null && $value !== '';
        });

        return $this->httpClient->sendRequest($this->partnerCardsEndpoint, $params, $this->httpClient::METHOD_POST);
    }

    /**
     * Get Partner Cards List
     * Retrieve a paginated list of all partner cards for the business.
     *
     * @param int|null $page
     * @param int|null $per_page
     * @return object
     */
    public function listPartnerCards(int $page = null, int $per_page = null)
    {
        return $this->httpClient->sendRequest($this->partnerCardsEndpoint, [], HttpClient::METHOD_GET, ['page' => $page, 'per_page' => $per_page]);
    }

    /**
     * Get Partner Card Details
     * Retrieve detailed information about a specific partner card using its identifier.
     *
     * @param string $identifier Partner card identifier
     * @return object
     */
    public function getPartnerCard(string $identifier)
    {
        return $this->httpClient->sendRequest($this->partnerCardsEndpoint . '/' . $identifier);
    }

    /**
     * Update Partner Card
     * Update the amount limit of an active partner card. Only active cards can be updated.
     *
     * @param string $identifier Partner card identifier
     * @param float $amount The new amount limit for the partner card
     * @return object
     */
    public function updatePartnerCard(string $identifier, float $amount)
    {
        return $this->httpClient->sendRequest(
            $this->partnerCardsEndpoint . '/' . $identifier,
            ['amount' => $amount],
            HttpClient::METHOD_PATCH
        );
    }

    /**
     * Cancel Partner Card
     * Cancel an active partner card. Only active cards can be cancelled.
     *
     * @param string $identifier Partner card identifier
     * @return object
     */
    public function cancelPartnerCard(string $identifier)
    {
        return $this->httpClient->sendRequest(
            $this->partnerCardsEndpoint . '/' . $identifier . '/cancel',
            [],
            HttpClient::METHOD_PATCH
        );
    }

    /**
     * Get Partner Card Transactions
     * Retrieve a paginated list of all transactions for a specific partner card.
     *
     * @param string $identifier Partner card identifier
     * @param int|null $page
     * @param int|null $per_page
     * @return object
     */
    public function listPartnerCardTransactions(string $identifier, int $page = null, int $per_page = null)
    {
        return $this->httpClient->sendRequest(
            $this->partnerCardsEndpoint . '/' . $identifier . '/transactions',
            [],
            HttpClient::METHOD_GET,
            ['page' => $page, 'per_page' => $per_page]
        );
    }

    /**
     * Get Partner Card Transaction Details
     * Retrieve detailed information about a specific transaction for a partner card.
     *
     * @param string $identifier Partner card identifier
     * @param string $transaction_identifier Transaction identifier
     * @return object
     */
    public function getPartnerCardTransaction(string $identifier, string $transaction_identifier)
    {
        return $this->httpClient->sendRequest(
            $this->partnerCardsEndpoint . '/' . $identifier . '/transactions/' . $transaction_identifier
        );
    }

    /**
     * Fetching Card Transactions
     * Fetches all transactions for cards
     *
     * @param int|null $page
     * @param int|null $per_page
     * @return object
     */
    public function transactions(int $page = null, int $per_page = null)
    {
        return $this->httpClient->sendRequest('/cards/transactions', [], HttpClient::METHOD_GET, ['page' => $page, 'per_page' => $per_page]);
    }

    /**
     * Fetch Transaction Details
     * Fetches detailed information for a specific card transaction
     *
     * @param string $transactionId Card transaction identifier
     * @return object
     */
    public function getTransaction(string $transactionId)
    {
        return $this->httpClient->sendRequest('/cards/transactions/' . $transactionId);
    }

    /**
     * Update Expense
     * Updates expense details such as description, invoice number, and status
     *
     * @param string $expenseId Expense identifier
     * @param string|null $invoice_number Invoice number for the expense
     * @param string|null $description Description of the expense
     * @param string|null $status Status of the expense: incomplete, pending_review, ready or synced
     * @return object
     */
    public function updateExpense(string $expenseId, string $invoice_number = null, string $description = null, string $status = null)
    {
        $params = array_filter([
            'invoice_number' => $invoice_number,
            'description' => $description,
            'status' => $status,
        ], function ($value) {
            return $value !== null;
        });

        return $this->httpClient->sendRequest('/expenses/' . $expenseId, $params, HttpClient::METHOD_PATCH);
    }

    /**
     * Fetching Expense Receipts
     * Fetches all expense receipts for a given business
     *
     * @param int|null $page
     * @param int|null $per_page
     * @return object
     */
    public function expenseReceipts(int $page = null, int $per_page = null)
    {
        return $this->httpClient->sendRequest('/expenses/receipts', [], HttpClient::METHOD_GET, ['page' => $page, 'per_page' => $per_page]);
    }

    /**
     * Fetch Expense Receipt Details
     * Fetches detailed information for a specific expense receipt
     *
     * @param string $identifier Receipt identifier
     * @return object
     */
    public function getExpenseReceipt(string $identifier)
    {
        return $this->httpClient->sendRequest('/expenses/receipts/' . $identifier);
    }
}
