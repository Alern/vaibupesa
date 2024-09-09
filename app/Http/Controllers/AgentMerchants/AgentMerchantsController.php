<?php

namespace App\Http\Controllers\AgentMerchants;

use App\Http\Controllers\Controller;
use App\Models\AgentMerchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AgentMerchantsController extends Controller
{
    public function create(Request $request)
    {
        // dd($request->all());
        $rules = array(
            'entitytype' => 'required',
            'identcode' => 'required',
            'orgname' => 'required',
            'orglocation' => 'required',
            'orgdescription' => 'required',
            'orgbalance' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        // process the login
        if ($validator->fails()) {
            Session::flash('error', 'Validation failed, check your input!!');
            return Redirect::to('/agentmerchants/create')
                ->withErrors($validator);
        } else {
            // store
            $agntmerchant = new AgentMerchant;
            $agntmerchant->entitytype = $request->entitytype;
            $agntmerchant->identcode = $request->identcode;
            $agntmerchant->orgname = $request->orgname;
            $agntmerchant->orglocation = $request->orglocation;
            $agntmerchant->orgdescription = $request->orgdescription;
            $agntmerchant->orgbalance = $request->orgbalance;
            $agntmerchant->save();
            // redirect
            Session::flash('message', 'Successfully created an organization! Bingo!!!');
            return Redirect::to('/agentmerchants/create');
        }
    }
}
