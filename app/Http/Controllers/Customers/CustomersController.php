<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CustomersController extends Controller
{
    public function create(Request $request){
        $rules = array(
            'fname' => 'required',
            'lname' => 'required',
            'msisdn' => 'required',
            'email' => 'required|email',
            'idno' => 'required',
            'gender' => 'required',
            'reglocation' => 'required',
            'topupamnt' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            Session::flash('error', 'Validation failed, check your input!!!');
            return Redirect::to('/customers/create')
                ->withErrors($validator);
        } else {
            // store
            $customer = new Customer;
            $customer->fname = $request->fname;
            $customer->lname = $request->lname;
            $customer->msisdn = $request->msisdn;
            $customer->email = $request->email;
            $customer->idno = $request->idno;
            $customer->gender = $request->gender;
            $customer->reglocation = $request->reglocation;
            $customer->topupamnt = $request->topupamnt;
            $customer->save();
            // redirect
            Session::flash('message', 'Successfully created a customer! Bingo!!!');
            return Redirect::to('/customers/create');
        }
    }
}
