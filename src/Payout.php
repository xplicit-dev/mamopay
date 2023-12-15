<?php

namespace MamoPay\Api;

use MamoPay\Api\Objects\Disbursement;
use MamoPay\Api\Objects\DisbursementInfo;

class Payout
{
    private HttpClient $httpClient;

    private $endpoint = '/disbursements/';

    public function __construct($httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Fetch all Disbursement Info
     *
     * @return array<DisbursementInfo>
     */
    public function all()
    {
        return $this->httpClient->sendRequest($this->endpoint);
    }

    /**
     * Allows the issuance of disbursements in bulk
     *
     * @param Disbursement $disbursement
     * @return DisbursementInfo
     */
    public function issue($account_no, $amount, $first_name, $last_name = '', $reason = '', $transfer_method = 'BANK_ACCOUNT')
    {
        $disbursement = (new Disbursement());
        $disbursement->set([
            'account' => $account_no,
            'amount' => $amount,
            'first_name_or_business_name' => $first_name,
            'last_name' => $last_name,
            'reason' => $reason,
            'transfer_method' => $transfer_method,
        ]);
        $disbursementInfo = $this->httpClient->sendRequest($this->endpoint, ['disbursements' => [$disbursement]], HttpClient::METHOD_POST);
        if (!empty($disbursementInfo['status']) && $disbursementInfo['status'] == false) {
        } else {
            $disbursementInfo = json_decode(json_encode($disbursementInfo), true);
            $disbursementInfo = (object) $disbursementInfo[0];
        }

        return $disbursementInfo;
    }

    /**
     * Allows the issuance of disbursements in bulk
     *
     * @param Disbursement[] $disbursements An array of DisbursementInfo objects.
     * @return array<DisbursementInfo>
     */
    public function issueMultiple(array $disbursements)
    {
        return $this->httpClient->sendRequest($this->endpoint, ['disbursements' => $disbursements], HttpClient::METHOD_POST);
    }

    /**
     * Fetch Disbursement Info
     *
     * @param string $subscriptionId
     * @return DisbursementInfo
     */
    public function get(string $disbursementId)
    {
        return $this->httpClient->sendRequest($this->endpoint . '/' . $disbursementId);
    }
}
