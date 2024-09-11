@include('layouts.navigation.navbar')

<center>
<h3>Statement for {{ $phone }}, {{ $fname }} {{ $lname }}, dates: {{ $sdate }} to {{ $edate }}. </h3>
</center>

<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <td>Transaction Ref_ID</td>
        <td>Transaction Ref_Code</td>
        <td>sender_user_id</td>
        <td>receiver_user_id</td>
        <td>transaction_type_id</td>
        <td>transaction_amount</td>
        <td>transaction_cost</td>
        <td>sender_balance</td>
        <td>transaction_code</td>
        <td>receiver_balance</td>
        <td>created_at</td>
        <td>updated_at</td>
    </tr>
    </thead>
    <tbody>
    @foreach($transactions as $transaction)
        <tr>
            <td>{{ $transaction->transaction_ref_id }}</td>
            <td>{{ $transaction->transaction_ref_code }}</td>
            <td>{{ $transaction->sender_user_id }}</td>
            <td>Ksh.{{ $transaction->receiver_user_id }}</td>
            <td>Ksh.{{ $transaction->transaction_type_id }}</td>
            <td>Ksh.{{ $transaction->transaction_amount }}</td>
            <td>Ksh.{{ $transaction->transaction_cost }}</td>
            <td>{{ $transaction->sender_balance }}</td>
            <td>{{ $transaction->transaction_code }}</td>
            <td>{{ $transaction->receiver_balance }}</td>
            <td>{{ $transaction->created_at }}</td>
            <td>{{ $transaction->updated_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
