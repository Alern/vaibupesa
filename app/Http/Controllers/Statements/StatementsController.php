<?php

namespace App\Http\Controllers\Statements;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatementsController extends Controller
{
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

        return view('pages.statements.statementsresults', compact('transactions','phone','sdate','edate','fname','lname',
            'senderfname','senderlname','receiverfname','receiverlname','transactionname','otherpartymsisdn'));
    }
    public function statementsForSelf(Request $request){

        $user = auth()->user();
        $phone=$user->msisdn;

       // dd($phone);
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

        return view('pages.statements.statementsresults', compact('transactions','phone','sdate','edate','fname','lname',
            'senderfname','senderlname','receiverfname','receiverlname','transactionname','otherpartymsisdn'));
    }
}
