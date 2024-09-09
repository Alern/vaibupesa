@include('layouts.navigation.navbar')

<div class="container">

    <h1>Summary Revenue</h1>
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <td>Today</td>
            <td>Yesterday</td>
            <td>Last 7 Days</td>
            <td>This Week</td>
            <td>This Month</td>
            <td>Total</td>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td>Ksh.{{ $todayRevenue }}</td>
                <td>Ksh.{{ $yesterdayRevenue }}</td>
                <td>Ksh.{{ $last7daysRevenue}}</td>
                <td>Ksh.{{ $thisWeekRevenue}}</td>
                <td>Ksh.{{ $thisMonthRevenue}}</td>
                <td>Ksh.{{$totalRevenue}}</td>
            </tr>
        </tbody>
    </table>

    @include('layouts.includes.session_flash')

    <h1>Detailed Revenue</h1>

    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <td>Id</td>
            <td>Transaction Ref_Id</td>
            <td>Transaction Cost (Kes.)</td>
            <td>Time</td>
        </tr>
        </thead>
        <tbody>
        @foreach($get_revenue as $getrevenue)
            <tr>
                <td>{{ $getrevenue->id }}</td>
                <td>{{ $getrevenue->transaction_ref_id }}</td>
                <td>Ksh.{{ $getrevenue->transaction_cost}}</td>
                <td>{{ $getrevenue->created_at}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
