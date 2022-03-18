<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckNipFormRequest;
use App\Http\Requests\MakePaymentFormRequest;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Services\PaynowService;
use App\Services\RegonService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class MakePaymentController extends Controller
{
    //
    public function __invoke(PaynowService $service, Request  $request) // @TODO validator
    {
        $res = [];
        $data = $request->all();
        $res = ['nip' => Arr::get($data, 'nip')];

        $user_id = Auth::id();
        $user =  User::find( $user_id );

        $amount = 100;
        $currency = 'USD';
        $user_email = $user->email;
        $order_description = 'ORDER 1';
        $order_reference = 'ORDER 1';

        $order = new Order();
        $order->id_user = $user_id;
        $order->amount = $amount;
        $order->currency = $currency;
        $order->description = $order_description;
        $order->reference = $order_reference;
        $order->status = 'ON PAYMENT';
        $order->save();

        // create Order Model - create sub Payment record

        $payment = $service->makePayment($order_reference, $order_description, $user_email, $amount, $currency);



            if ($payment) {

                $paymentModel = new Payment();
                $paymentModel->id_order = $order->id; // @TODO with relation
                $paymentModel->gateway = 'paynow';
                $paymentModel->gateway_reference = $payment['payment_id'];
                $paymentModel->amount = $amount;
                $paymentModel->status = $payment['payment_status'] . ' - REDIRECTED';
                $paymentModel->result = 'WAITING PAYMENT';
                $paymentModel->save();

                return redirect($payment['redirect_uri']);

            } else {
                $res += ['message' => __('paynow.payment_failed')];
            }

        return redirect()->route('home')->with($res);
    }
}
