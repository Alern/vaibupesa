<?php

namespace App\Http\Controllers\ChargeBands;

use App\Http\Controllers\Controller;
use App\Models\ChargeBand;
use App\Models\TransactionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ChargeBandsController extends Controller
{
    public function create(Request $request)
    {
        $rules = array(
            'transactiontypeid' => 'required',
            'chargebandname' => 'required',
            'loweramount' => 'required',
            'upperamount' => 'required',
            'tcost' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        // process the login
        if ($validator->fails()) {
            Session::flash('error', 'Chargeband Failed to Add!!!');
            return Redirect::to('/chargebands/create')
                ->withErrors($validator);
        } else {
            // store
            $charge = new ChargeBand;
            $charge->transactiontypeid = $request->transactiontypeid;
            $charge->chargebandname = $request->chargebandname;
            $charge->loweramount = $request->loweramount;
            $charge->upperamount = $request->upperamount;
            $charge->tcost = $request->tcost;
            $charge->save();
            // redirect
            Session::flash('message', 'Successfully created a Charge Band! Bingo!!!');
            return Redirect::to('/chargebands/create');

        }
    }
}
