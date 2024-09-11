@include('layouts.navigation.navbar')

<div class="container">

    <h1>Transactions</h1>

    @include('layouts.includes.session_flash')

    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <td>Transaction Ref_ID</td>
            <td>Transaction Ref_Code</td>
            <td>transaction_type_id</td>
            <td>transaction_amount</td>
            <td>transaction_cost</td>
            <td>sender_balance</td>
            <td>receiver_balance</td>
            <td>timestamp</td>
        </tr>
        </thead>
        <tbody>
        @foreach($get_transactions as $get_transaction)
            <tr>
                <td>{{ $get_transaction->transaction_ref_id }}</td>
                <td>{{ $get_transaction->transaction_ref_code }}</td>
                <td>{{ $get_transaction->transaction_type_id }}</td>
                <td>Ksh.{{ $get_transaction->transaction_amount }}</td>
                <td>Ksh.{{ $get_transaction->transaction_cost }}</td>
                <td>Ksh.{{ $get_transaction->sender_balance }}</td>
                <td>Ksh.{{ $get_transaction->receiver_balance }}</td>
                <td>{{ $get_transaction->created_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
