<?php

namespace MamoPay\Api;

/**
 * This class represents the mamopay Virtual Corporate Card
 */
class Cards
{
    private HttpClient $httpClient;
    
    private $endpoint = '/vcc_cards';

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
}
