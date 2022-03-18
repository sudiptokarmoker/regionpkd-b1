<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\InvoiceoceanService;
use App\Services\PaynowService;
use App\Services\RegonService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ValidationPaymentController extends Controller
{
    //
    public function __invoke( Request $request, PaynowService $service, InvoiceoceanService $invoiceService, RegonService $regonService){
        $data = $request->all();

        $user_id = Auth::id();
        $user =  User::find( $user_id );
        $res = [ 'nip' => $user->nip ];

        $company = $regonService->searchRecord($res);
        $payment = $service->validatePayment( Arr::get($data, 'paymentId') );


            if ($payment) {

                $product = [
                    'name' => 'Produkt A1',
                    'tax' => 23,
                    'total_price_gross' =>100,
                    'quantity' => 1,
                ];

                $company = [
                    'company' => $company['name'],
                    'email' => $user->email,
                    'vat_number' => $company['nip']
                ];

                $response = $invoiceService->addInvoice(
                    date('Y-m-d'),
                    $company,
                    $product
                );
                if ($response['success'] === true AND isset($response['response']->id) ) {

                    $x = $invoiceService->sendInvoice($response['response']->id); // Invoice will be sent to buyer_email

                }

                $res += ['message' => __('paynow.payment_complete')];
            } else {
                $res += ['message' => __('paynow.payment_fail')];
            }

        return redirect()->route('home')->with($res);
    }
}
