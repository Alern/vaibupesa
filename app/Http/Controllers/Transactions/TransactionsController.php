<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Models\ChargeBand;
use App\Models\Customer;
use App\Models\Notification;
use App\Models\Revenue;
use App\Models\Transaction;
use App\Models\TransactionType;
use Dotenv\Parser\Parser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TransactionsController extends Controller
{

    public function sendRegistered(Request $request){
        $user = auth()->user();
        $inputs1=$user->msisdn;
        $registeredPin=$user->password;

        $rules = array(
            'receiverMsisdn' => 'required',
            'amount' => 'required',
            'password' => 'required',
        );
        $request->validate($rules);
        $inputs2 = $request->input('receiverMsisdn');
        $inputs3 = $request->input('amount');
        $pin = $request->input('password');

        $hakikishafname=DB::table('customers')->where('msisdn', '=', $inputs2)->pluck('fname')->first();
        $hakikishalname=DB::table('customers')->where('msisdn', '=', $inputs2)->pluck('lname')->first();


        if($inputs3 > 0 and $inputs3 <= 50){
            $hakikishatcost=100;
        }
        else if($inputs3 > 50 and $inputs3 <= 100){
            $hakikishatcost=200;
        }
        else if($inputs3 > 100 and $inputs3 <= 150){
            $hakikishatcost=300;
        }
        else if($inputs3 > 10 and $inputs3 <= 200){
            $hakikishatcost=400;
        }
        else{
            $hakikishatcost=500;
        }

        if(Hash::check($pin, $registeredPin)) {
            return view('pages.transact.hakikisha', compact('hakikishafname', 'hakikishalname','inputs1','inputs2','inputs3','hakikishatcost'));
        } else{
            return view('pages.transact.hakikisha');
        }



    }
    public function validateInput(Request $request){
        $rules = array(
            'scustomer' => 'required',
            'rcustomer' => 'required',
            'amount' => 'required',
        );
        $request->validate($rules);
        $inputs1 = $request->input('scustomer');
        $inputs2 = $request->input('rcustomer');
        $inputs3 = $request->input('amount');

        $hakikishafname=DB::table('customers')->where('msisdn', '=', $inputs2)->pluck('fname')->first();
        $hakikishalname=DB::table('customers')->where('msisdn', '=', $inputs2)->pluck('lname')->first();

        if($inputs3 > 0 and $inputs3 <= 50){
            $hakikishatcost=100;
        }
        else if($inputs3 > 50 and $inputs3 <= 100){
            $hakikishatcost=200;
        }
        else if($inputs3 > 100 and $inputs3 <= 150){
            $hakikishatcost=300;
        }
        else if($inputs3 > 10 and $inputs3 <= 200){
            $hakikishatcost=400;
        }
        else{
            $hakikishatcost=500;
        }


       // dd($inputs);
        return view('pages.transact.hakikisha', compact('hakikishafname', 'hakikishalname','inputs1','inputs2','inputs3','hakikishatcost'));


    }

    public function hakikishaCancel(){
        return view('pages.transact.create');
    }

    public function create(Request $request)
    {
        $sender_customer_id = $request->input('senderphone');
        $receiver_customer_id = $request->input('receiverphone');
        $transaction_amount = $request->input('tamount');
        $transact_amount_cost = $request->input('tcost');

      //dd($sender_customer_id,$receiver_customer_id,$transaction_amount,$transact_amount_cost);

        //initial query balance of the sender and receiver
        //$sender_query_balance = Customer::where('msisdn', '=', $sender_customer_id)->pluck('topupamnt')->first();
        //$receiver_query_balance = Customer::where('msisdn', '=', $receiver_customer_id)->pluck('topupamnt')->first();

        $sender_query_balance=DB::table('customers')->where('msisdn', '=', $sender_customer_id)->pluck('topupamnt')->first();
        $receiver_query_balance=DB::table('customers')->where('msisdn', '=', $receiver_customer_id)->pluck('topupamnt')->first();



        //get receiver kyc for verification
        $receiver_query_kyc = Customer::where('msisdn', '=', $receiver_customer_id)->sum('msisdn');
        $transaction_type_id = TransactionType::where('tname', '=', 'Send Cash')->sum('id');
        $random = Str::random(10);
        $randomId = rand(1000,9999);



        //total transaction amount with transaction charge
        $total_transaction_cost=$transaction_amount+$transact_amount_cost;

        //calculate the new balances after transaction
        $new_receiver_balance= $receiver_query_balance + $transaction_amount;
        $new_sender_balance= $sender_query_balance - $total_transaction_cost;



        //validate balance, transaction amount and sender vs receiver before the transaction
        if($new_sender_balance < 0 ){
            Session::flash('error', 'Insufficient Balance for Sending'.' '. 'Kes'.' '. $transaction_amount.', '.'Top Up and Retry!!');
            return Redirect::to('/transactions/create');
        }
        else if($transaction_amount==0 or $transaction_amount < 0){
            Session::flash('error', 'Transaction value not permitted!');
            return Redirect::to('/transactions/create');
        }
        else if($sender_customer_id==$receiver_customer_id){
            Session::flash('error', 'You cannot transfer money to yourself, select a different recipient');
            return Redirect::to('/transactions/create');
        }
        else {

            //begin laravel transaction
            DB::beginTransaction();
            try {

                //update balance on customers table
                DB::table('customers')->where('msisdn', $receiver_customer_id)->update(['topupamnt' => $new_receiver_balance]);
                DB::table('customers')->where('msisdn', $sender_customer_id)->update(['topupamnt' => $new_sender_balance]);
                $updated_sender_balance = DB::table('customers')->where('msisdn', '=', $sender_customer_id)->sum('topupamnt');


                //update the record on transactions table
                $input_transaction = [
                    'transaction_ref_id' => $randomId,
                    'transaction_ref_code' => $random,
                    'customer_msisdn' => $sender_customer_id,
                    'receiver_msisdn' => $receiver_customer_id,
                    'transaction_type_id' => $transaction_type_id,
                    'transaction_amount' => $transaction_amount,
                    'transaction_cost' => $transact_amount_cost,  //hardcoded for a start, to be extracted from the charge bands
                    'transaction_code' => $random,
                    'sender_balance' => $new_sender_balance,
                    'receiver_balance' => $new_receiver_balance,
                    "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                    "updated_at" => \Carbon\Carbon::now()  # new \Datetime()
                ];
                //dd($input_transaction);
                DB::table('transactions')->insert($input_transaction);

                //update the revenue table
                $input_revenue = [
                    'transaction_ref_id' => $randomId,
                    'transaction_cost' => $transact_amount_cost,  //hardcoded for a start, to be extracted from the charge bands
                    "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                    "updated_at" => \Carbon\Carbon::now()  # new \Datetime()
                ];
                DB::table('revenues')->insert($input_revenue);

                //Notifications message
                $notification = 'Kes'.' '.$transaction_amount.' '.'Sent to'.' 0'.$receiver_query_kyc.' Transaction cost is Kes '.$transact_amount_cost.'. Your account balance is Kes'.' '.$updated_sender_balance.'.';

                //Update the notification table
//                $input_notification = [
//                    'transaction_ref_id' => $randomId,
//                    'notification' => $notification
//                ];
//                DB::table('notifications')->create($input_notification);

                //commit transactions if no exceptions

                DB::commit();

                //Session flash the sender with sent amount and new account balance
                Session::flash('message', $notification);
                return Redirect::to('/transactions/create');

                //throw exception and rollback if issue occured
            } catch (\Exception $e) {
                // Rollback the transaction if any operation fails
                DB::rollBack();
                return back()->with('error', 'A concurrency error occurred while updating the records in the db, resolve and try again.')->withInput();
            }
        }
    }

    public function showTransactions(){
        $get_transactions = Transaction::orderBy('created_at', 'DESC')->get();
        return view('pages.transact.view', compact('get_transactions'));
    }

    public function showNotifications(){
        $get_notifications = Notification::all();
        return view('pages.notifications.view', compact('get_notifications'));
    }

    public function showRevenue(){

        $now = \Carbon\Carbon::now();
        $startOfToday = \Carbon\Carbon::now()->startOfDay();
        $yesterdayStartOfDay=\Carbon\Carbon::now()->subDay(1)->startOfDay();
        $yesterdayEndOfDay=\Carbon\Carbon::now()->subDay(1)->endOfDay();
        $last7days = \Carbon\Carbon::now()->subDay(7);
        $thisWeek = \Carbon\Carbon::now()->startofWeek();
        $thisMonth = \Carbon\Carbon::now()->startOfMonth();

        $todayRevenue=DB::table('transactions')
            ->whereBetween('created_at', [$startOfToday, $now])
            ->sum('transaction_cost');

        $yesterdayRevenue=DB::table('transactions')
            ->whereBetween('created_at', [$yesterdayStartOfDay, $yesterdayEndOfDay])
            ->sum('transaction_cost');

        $last7daysRevenue=DB::table('transactions')
            ->whereBetween('created_at', [$last7days, $now])
            ->sum('transaction_cost');

        $thisWeekRevenue=DB::table('transactions')
            ->whereBetween('created_at', [$thisWeek, $now])
            ->sum('transaction_cost');

        $thisMonthRevenue=DB::table('transactions')
            ->whereBetween('created_at', [$thisMonth, $now])
            ->sum('transaction_cost');

        $totalRevenue=DB::table('transactions')->sum('transaction_cost');

        $get_revenue = Transaction::orderBy('created_at', 'DESC')->get();
        return view('pages.revenue.view', compact('get_revenue','todayRevenue','yesterdayRevenue','last7daysRevenue','thisWeekRevenue','thisMonthRevenue','totalRevenue'));
    }

}


