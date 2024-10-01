<?php

namespace App\Http\Controllers\RegisteredCustomers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\TransactionType;
use App\Models\User;
use Carbon\Carbon;
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
    public function redirectUnregisteredTxn(){
        $user = auth()->user();
        $inputs1=$user->id;
        $loggedinuserbal=DB::table('customers')->where('user_id', '=', $inputs1)->pluck('topupamnt')->first();

        if($loggedinuserbal == 0){
                return back()->with('error', 'Our Admins are working on updating your balance for you to transact, please be patient.');
        }else
        {return redirect()->intended('/registeredcustomers/create');
        }
    }
    public function sendRegistered(Request $request){
        $user = auth()->user();
        $inputs1=$user->msisdn;
        $registeredPin=$user->password;

        $rules = array(
            'receiverMsisdn' => 'required',
            'amount' => 'required|numeric',
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
    public function kycLanding(){
        $user = auth()->user();
        $userid=$user->id;

        $current = Carbon::now();
        $startOfWeek = Carbon::now()->startOfWeek();

        $accountBal=DB::table('customers')->where('user_id', '=', $userid)->pluck('topupamnt')->first();
        $transactedToday=DB::table('transactions')->where('sender_user_id', '=', $userid)->
        orWhere('receiver_user_id', '=', $userid)->whereBetween('created_at', [$current, $startOfWeek])->count();

       // dd($transactedToday);

        return view('pages.registeredcustomers.landing', compact('accountBal','transactedToday'));
    }
    public function noKycLanding(){
        $user = auth()->user();
        $userid=$user->id;
        return view('pages.registeredcustomers.nokyclanding', compact('userid'));
    }
    public function editKyc(){
        $user = auth()->user();
        $userid=$user->id;
        $loggedinuserdetails=DB::table('customers')->where('user_id', '=', $userid)->first();

        if(is_null($loggedinuserdetails)){
            return view('pages.registeredcustomers.updatekycnew', compact('loggedinuserdetails'));
        }else{
            return view('pages.registeredcustomers.updatekyc', compact('loggedinuserdetails'));
        }
    }
    public function updateKycnew(Request $request){
        $rules = array(
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required|email',
            'idno' => 'required',
            'gender' => 'required',
            'reglocation' => 'required',
            //'topupamnt' => 'required'
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
            $customer->topupamnt = 0;
            $customer->save();

            // redirect
            $user_bal=DB::table('customers')->where('user_id', '=', $userid)->pluck('topupamnt')->first();

            if(is_null($user_bal)){
                Session::flash('message', 'Successfully updated your KYC!');
                return Redirect::to('/registered/nokyclandingpage');
            }
            else{
                Session::flash('message', 'Successfully updated your KYC!');
                return Redirect::to('/registered/landingpage');
            }
        }
    }
    public function updateKyc(Request $request){

        $user = auth()->user();
        (int)$inputs1=$user->id;

        $loggedinuserdetails=DB::table('customers')->where('user_id', '=', $inputs1)->pluck('id')->first();
      // dd($loggedinuserdetails);

            $customer = Customer::find($loggedinuserdetails);
           // $loggedinuserdetails=DB::table('customers')->where('user_id', '=', $inputs1)->limit(1);

           //dd($customer);
           // $customer->user_id= $user->id;
        $customer->fname= $request->input('fname');
        $customer->lname= $request->input('lname');
        $customer->email= $request->input('email');
        $customer->idno= $request->input('idno');
        $customer->gender= $request->input('gender');
        $customer->reglocation= $request->input('reglocation');
        $customer->update();

      //  DB::table('customers')->insert($loggedinuserdetails);

        Session::flash('message', 'Successfully updated your KYC!');
        return Redirect::to('/registered/landingpage');
    }
    public function adminDash(){
        $totalCash= DB::table('customers')->sum('topupamnt');
        $totalRevenue=DB::table('transactions')->sum('transaction_cost');
        $totalTxnCount=DB::table('transactions')->count();
        $totalCustCount=DB::table('customers')->count();

        $balUpdate=DB::table('customers')->whereNull('topupamnt')->get();
        return view('layouts.dashboard.dashboardadmin', compact('balUpdate',
            'totalCash','totalRevenue','totalTxnCount','totalCustCount'));
    }
    public function editBalance($id)
    {
        $customer = Customer::find($id);
        return view('pages.registeredcustomers.updatebalance', compact('customer'));
    }
    public function updateBalance(Request $request, $id)
    {
        $customer = Customer::find($id);
        $customer->topupamnt= $request->input('topupamnt');
        $customer->update();
        return redirect()->back()->with('status','Customer Balance Updated Successfully');
    }
    public function hakikishaCancel(){
        return view('pages.registeredcustomers.createregistered');
    }
    public function showBalance(){
        $user = auth()->user();
        $sender_identity_id=$user->id;
        $sender_query_balance=DB::table('customers')->where('user_id', '=', $sender_identity_id)->pluck('topupamnt')->first();
        return view('pages.registeredcustomers.createregistered', compact('sender_query_balance'));
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

        $sender_query_balance=DB::table('customers')->where('user_id', '=', $sender_identity_id)->pluck('topupamnt')->first();
        $receiver_query_balance=DB::table('customers')->where('user_id', '=', $receiver_identity_id)->pluck('topupamnt')->first();

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
    public function statements(Request $request){

        $phone = $request->input('phone');
        $sdate = $request->input('startdate');
        $edate = $request->input('enddate');

        //dd($phone, $sdate, $edate);

        $phone_id=DB::table('users')->where('msisdn', '=', $phone)->pluck('id')->first();

        $fname=DB::table('customers')->where('user_id', '=', $phone_id)->pluck('fname')->first();
        $lname=DB::table('customers')->where('user_id', '=', $phone_id)->pluck('lname')->first();

        $transactions=DB::table('transactions')->where('sender_user_id', '=', $phone_id)->
        orWhere('receiver_user_id', '=', $phone_id)->whereBetween('created_at', [$sdate, $edate])->get();

        $otherpartyid1=DB::table('transactions')->where('sender_user_id', '=', $phone_id)->pluck('receiver_user_id')->first();
        $otherpartyid2=DB::table('transactions')->where('receiver_user_id', '=', $phone_id)->pluck('sender_user_id')->first();

        $senderfname=DB::table('customers')->where('user_id', '=', $phone_id)->pluck('fname')->first();
        $senderlname=DB::table('customers')->where('user_id', '=', $phone_id)->pluck('lname')->first();
        $receiverfname=DB::table('customers')->where('user_id', '=', $otherpartyid1)->
        orWhere('user_id', '=', $otherpartyid2)->pluck('fname')->first();
        $receiverlname=DB::table('customers')->where('user_id', '=', $otherpartyid1)->
        orWhere('user_id', '=', $otherpartyid2)->pluck('lname')->first();

        $transactiontypeid=DB::table('transactions')->where('sender_user_id', '=', $phone)->
        orWhere('receiver_user_id', '=', $phone_id)->pluck('transaction_type_id')->first();

        $transactionname=DB::table('transaction_types')->where('id', '=', $transactiontypeid)->
        pluck('tname')->first();

        $otherpartymsisdn=DB::table('users')->where('id', '=', $otherpartyid1)->
        orWhere('id', '=', $otherpartyid2)->pluck('msisdn')->first();

        return view('pages.registeredcustomers.statementsresults', compact('transactions','phone','sdate','edate','fname','lname',
            'senderfname','senderlname','receiverfname','receiverlname','transactionname','otherpartymsisdn'));
    }

}
