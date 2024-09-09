<?php

namespace App\Http\Controllers\TransactionTypes;

use App\Http\Controllers\Controller;
use App\Models\TransactionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class TransactionTypesController extends Controller
{
public function create(Request $request)
{
    $rules = array(
        'tname' => 'required',
        'tdescription' => 'required',
    );
    $validator = Validator::make($request->all(), $rules);
    // process the login
    if ($validator->fails()) {
        Session::flash('error', 'TransactionType Failed to Add!!!');
        return Redirect::to('/transactiontypes/create')
            ->withErrors($validator);
    } else {
        // store
        $transaction = new TransactionType;
        $transaction->tname = $request->tname;
        $transaction->tdescription = $request->tdescription;

        $transaction->save();
        // redirect
        Session::flash('message', 'Successfully created a TransactionType! Bingo!!!');
        return Redirect::to('/transactiontypes/create');

    }
}

}
