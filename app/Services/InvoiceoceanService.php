<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Services\Common\InvoiceOceanClient;


class InvoiceoceanService{
    protected $username;
    protected $token;

    protected $client;

    public function __construct()
    {
        $this->username = config('invoiceocean.api.username');
        $this->token = config('invoiceocean.api.token');
        $this->client = $this->getClient();
    }

    public function getClient(): InvoiceOceanClient
    {
        $client = new InvoiceOceanClient( $this->username, $this->token);
        return $client;
    }

    public function addInvoice( $date, $buyer, $product){
        $invoice  = [
            'kind' => 'vat',
            'number' => null,
            'sell_date' => $date,
            'issue_date' => $date,
            'payment_to' => $date,
            'seller_name' => 'Wystawca Sp. z o.o.',
            'seller_tax_no' => '5252445767',
            'buyer_name' => $buyer['company'],
            'buyer_email' => $buyer['email'],
            'buyer_tax_no' => $buyer['vat_number'],
            'positions' => [
                $product
            ],
        ];
        $result = $this->client->addInvoice($invoice);
        return $result;
    }
    public function sendInvoice( $invoiceId ){
        $result = $this->client->sendInvoice($invoiceId);
        return $result;
    }
    public function addClient(){

        $client = array(
            'name'          => 'Chris Schalenborgh',
            'tax_no'        => 1,
            'bank'          => 'My Bank',
            'bank_account'  => '001-123456-78',
            'city'          => 'Maasmechelen',
            'country'       => 'BE',
            'email'         => 'chris@schalenborgh.be',
            'person'        => '',
            'post_code'     => '1234',
            'phone'         => '+32.123456789',
            'street'        => 'Street',
            'street_no'     => '123'
        );

        $result = $this->client->addClient($client);
        return $result;
    }

}
