<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Paynow\Client;
use Paynow\Environment;
use Paynow\Service\Payment;
use Paynow\Exception\PaynowException;

class PaynowService
{
    protected $api_key;
    protected $signature_key;
    protected $environment;
    protected $client;

    public function __construct()
    {
        $this->api_key = config('paynow.api.api_key');
        $this->signature_key = config('paynow.api.signature_key');
        $this->environment = config('paynow.api.environment');

        if( !in_array( $this->environment, ['sandbox', 'production']) )
            die('Environment must be only sandbox or production');

        $this->client = $this->getClient();
    }

    public function getClient(): Client
    {
        $client = new Client($this->api_key, $this->signature_key, $this->environment);
        return $client;
    }

    public function makePayment( $order_reference, $order_description, $buyer_mail, $amount, $currency )
    {
        $idempotencyKey = uniqid($order_reference. '_');

        $amount = (string) number_format( $amount, 2, '', '');

        $paymentData = [
            'amount' => $amount,
            'currency' => $currency,
            'externalId' => $order_reference,
            'description' => $order_description,
            'buyer' => [
                'email' => $buyer_mail
            ]
        ];

        try {
            $payment = new Payment( $this->client );
            $result = $payment->authorize($paymentData, $idempotencyKey);
            return [
                'status' => 'success',
                'method' => 'redirect',
                'redirect_uri' => $result->getRedirectUrl(),
                'payment_status' => $result->getStatus(),
                'payment_id' => $result->getPaymentId(),
            ];
        } catch (PaynowException $exception) {
            Log::error('Paynow Payment Error:' . $exception);
            return null;
        }
    }

    public function validatePayment( $paymentId )
    {

        if( !$paymentId OR $paymentId == '' )
            return null;

        $payment = new Payment( $this->client );
        $result = $payment->status( $paymentId );
        if( $result->getStatus() == 'CONFIRMED' ){
            return true;
        } else {
            return false;
        }
    }

}
