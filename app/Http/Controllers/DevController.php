<?php

namespace App\Http\Controllers;

use App\Models\Dashboard\OrdersModel;
use Illuminate\Http\Request;
use App\Models\User;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DevController extends Controller
{
    public function index()
    {
        $checkBookWithAuthValidation = DB::table('orders')
                ->join('payments', 'payments.id_order', '=', 'orders.id') // this is main id
                ->select('orders.*', 'payments.id as payment_id', 'payments.created_at', 'payments.updated_at')
                ->where('orders.id_user', 1)
                ->where('payments.status', 'CONFIRMED')
                ->orderByDesc('payments.updated_at')
                ->first();
            
                dd($checkBookWithAuthValidation);
        $loadUserIsPremium = OrdersModel::where('id_user', 1)
            ->where('status', 'CONFIRMED')
            ->orderBy('created_at') // order by doing here becuase we would calculate the date from this point
            ->first();
        if($loadUserIsPremium){
            $differnce = Carbon::parse($loadUserIsPremium->created_at)->diffInDays(Carbon::now()->toDateTimeString(), false);
            if($differnce <= 183){
                dd("premium user");
            } else {
                dd("not premium user");
            }
            dd($differnce);
        } else{
            dd("false");
        }

        dd();
        //$url = 'https://sudiptocsi.invoiceocean.com/invoices.json?period=this_month&api_token=eluRVNKuerjqVTlfzrZ';
        //$crl = 'https://sudiptocsi.invoiceocean.com/invoices.json';
        $data = array(
            "api_token" => "eluRVNKuerjqVTlfzrZ",
            "invoice" => array(
                "kind" => "vat",
                "number" => null,
                "sell_date" => "2022-03-13",
                "issue_date" => "2022-03-13",
                "payment_to" => "2022-03-20",
                "seller_name" => "Seller SA",
                "seller_tax_no" => "5252445767",
                "buyer_name" => "Client1 SA",
                "buyer_tax_no" => "5252445767",
                "positions" => array(
                    [
                        "name" => "Produkt A1",
                        "tax" => 23,
                        "total_price_gross" => 10.23,
                        "quantity" => 1,
                    ],
                    [
                        "name" => "Produkt A2",
                        "tax" => 0,
                        "total_price_gross" => 50,
                        "quantity" => 2,
                    ],
                ),
            ),
        );

        $post_data = json_encode($data);

        // Prepare new cURL resource
        $crl = curl_init('https://sudiptocsi.invoiceocean.com/invoices.json');
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($crl, CURLINFO_HEADER_OUT, true);
        curl_setopt($crl, CURLOPT_POST, true);
        curl_setopt($crl, CURLOPT_POSTFIELDS, $post_data);

        // Set HTTP Header for POST request
        curl_setopt($crl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json'
        ));

        // Submit the POST request
        $result = curl_exec($crl);
        // Close cURL session handle
        curl_close($crl);
        dd($result);
    }

    public function gridRender(Request $request){
        if ($request->ajax()) {
            $data = User::orderBy('created_at', 'desc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function($user){
                    return $user->name;
                })
                ->addColumn('email', function($user){
                    return $user->email;
                })
                ->make(true);
        } else {
            return view('admin.grid-test');
        }
    }
}
