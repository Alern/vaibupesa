<?php

namespace App\Http\Controllers\RegisteredCustomers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\TransactionType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisteredCustomersController extends Controller
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

        $receiveruserid=DB::table('users')->where('msisdn', '=', $inputs2)->pluck('id')->first();
        $hakikishafname=DB::table('customers')->where('user_id', '=', $receiveruserid)->pluck('fname')->first();
        $hakikishalname=DB::table('customers')->where('user_id', '=', $receiveruserid)->pluck('lname')->first();

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

        if((Hash::check($pin, $registeredPin)) and (User::where('msisdn', $inputs2)->exists()) ) {
            return view('pages.registeredcustomers.hakikisha', compact('hakikishafname', 'hakikishalname','inputs1','inputs2','inputs3','hakikishatcost','receiveruserid'));
        } else{
           // return view('pages.registeredcustomers.createregistered');
            return back()->with('error', 'The provided customer details do not match our records.')->withInput();
        }
    }

    public function updateKyc(Request $request){
        $rules = array(
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required|email',
            'idno' => 'required',
            'gender' => 'required',
            'reglocation' => 'required',
            'topupamnt' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Session::flash('error', 'Validation failed, check your input!!!');
            return Redirect::to('/registered/updatekyc')
                ->withErrors($validator);
        } else {
            // store
            $user = auth()->user();
            $userid=$user->id;
            $customer = new Customer;
            $customer->user_id = $userid;
            $customer->fname = $request->fname;
            $customer->lname = $request->lname;
            $customer->email = $request->email;
            $customer->idno = $request->idno;
            $customer->gender = $request->gender;
            $customer->reglocation = $request->reglocation;
            $customer->topupamnt = $request->topupamnt;
            $customer->save();
            // redirect
            Session::flash('message', 'Successfully updated your KYC! You can proceed to transact!!!');
            return Redirect::to('/registeredcustomers/create');
        }
    }

    public function hakikishaCancel(){
        return view('pages.registeredcustomers.createregistered');
    }

    public function create(Request $request)
    {

        $user = auth()->user();
        $sender_identity_id=$user->id;

        $sender_customer_id = $request->input('senderphone');
        $receiver_customer_id = $request->input('receiverphone');
        $receiver_identity_id = $request->input('receiveridentity');
        $transaction_amount = $request->input('tamount');
        $transact_amount_cost = $request->input('tcost');

       // dd($sender_customer_id,$receiver_customer_id,$receiver_identity_id,$transaction_amount,$transact_amount_cost);

        $sender_query_balance=DB::table('customers')->where('user_id', '=', $receiver_identity_id)->pluck('topupamnt')->first();
        $receiver_query_balance=DB::table('customers')->where('user_id', '=', $sender_identity_id)->pluck('topupamnt')->first();

        //dd($sender_query_balance, $receiver_query_balance);

        //get receiver kyc for verification
        $receiver_query_kyc = User::where('id', '=', $receiver_identity_id)->pluck('msisdn')->first();
        $transaction_type_id = TransactionType::where('tname', '=', 'Send Cash')->sum('id');
        $random = Str::random(10);
        $randomId = rand(1000,9999);

       // dd($receiver_query_kyc,$transaction_type_id,$randomId);



        //total transaction amount with transaction charge
        $total_transaction_cost=$transaction_amount+$transact_amount_cost;

        //calculate the new balances after transaction
        $new_receiver_balance= $receiver_query_balance + $transaction_amount;
        $new_sender_balance= $sender_query_balance - $total_transaction_cost;

       // dd($total_transaction_cost,$new_receiver_balance,$new_sender_balance);


        //validate balance, transaction amount and sender vs receiver before the transaction
        if($new_sender_balance < 0 ){
            Session::flash('error', 'Insufficient Balance for Sending'.' '. 'Kes'.' '. $transaction_amount.', '.'Top Up and Retry!!');
            return Redirect::to('/registeredcustomers/create');
        }
        else if($transaction_amount==0 or $transaction_amount < 0){
            Session::flash('error', 'Transaction value not permitted!');
            return Redirect::to('/registeredcustomers/create');
        }
        else if($sender_customer_id==$receiver_customer_id){
            Session::flash('error', 'You cannot transfer money to yourself, select a different recipient');
            return Redirect::to('/registeredcustomers/create');
        }
        else {

            //begin laravel transaction
            DB::beginTransaction();
            try {
                //update balance on customers table
                DB::table('customers')->where('user_id', $receiver_identity_id)->update(['topupamnt' => $new_receiver_balance]);
                DB::table('customers')->where('user_id', $sender_identity_id)->update(['topupamnt' => $new_sender_balance]);
                $updated_sender_balance = DB::table('customers')->where('user_id', '=', $sender_identity_id)->sum('topupamnt');

                //update the record on transactions table
                $input_transaction = [
                    'transaction_ref_id' => $randomId,
                    'transaction_ref_code' => $random,
                    'sender_user_id' => $sender_identity_id,
                    'receiver_user_id' => $receiver_identity_id,
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

                //Notifications message
                $notification = 'Kes'.' '.$transaction_amount.' '.'Sent to'.' '.$receiver_query_kyc.' Transaction cost is Kes '.$transact_amount_cost.'. Your account balance is Kes'.' '.$updated_sender_balance.'.';

                DB::commit();

                //Session flash the sender with sent amount and new account balance
                Session::flash('message', $notification);
                return Redirect::to('/registeredcustomers/create');

                //throw exception and rollback if issue occured
            } catch (\Exception $e) {
                // Rollback the transaction if any operation fails
                DB::rollBack();
                return back()->with('error', 'A concurrency error occurred while updating the records in the db, resolve and try again.')->withInput();
            }
        }
    }
}
