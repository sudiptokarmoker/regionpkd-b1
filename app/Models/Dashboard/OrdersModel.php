<?php

namespace App\Models\Dashboard;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrdersModel extends Model
{
    use HasFactory;
    protected $table = 'orders';
    /**
     * check if the user is premium user or not
     */
    public static function checkIfUserHasPremiumAccess($user_id)
    {
        /**
         * "here requirements is not clear!!"
         * just we are just getting the order table data  according to the user id
         * and wiill check the any CONFIRMED payment term. if any confirmed payment found we would calculate the day of 183. and if current day is under 183 days then treat that user as premium user
         */
        $loadUserIsPremium = DB::table('orders')
                ->join('payments', 'payments.id_order', '=', 'orders.id') // this is main id
                ->select('orders.*', 'payments.id as payment_id', 'payments.created_at', 'payments.updated_at')
                ->where('orders.id_user', $user_id)
                ->where('payments.status', 'CONFIRMED')
                ->orderByDesc('payments.updated_at')
                ->first();
         /*
        $loadUserIsPremium = OrdersModel::where('id_user', $user_id)
            ->where('status', 'CONFIRMED')
            ->orderBy('created_at') // order by doing here becuase we would calculate the date from this point
            ->first();
        */
        if ($loadUserIsPremium) {
            $differnce = Carbon::parse($loadUserIsPremium->created_at)->diffInDays(Carbon::now()->toDateTimeString(), false);
            if ($differnce <= 183) {
                // here we are just returning the till date of premium access
                $premiumAccessTill = Carbon::parse($loadUserIsPremium->created_at)->addDays(183)->toFormattedDateString();
                return $premiumAccessTill;
                //return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
