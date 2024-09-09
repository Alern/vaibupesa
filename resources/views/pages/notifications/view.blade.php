@include('layouts.navigation.navbar')

<div class="container">

    <h1>Notifications</h1>

    @include('layouts.includes.session_flash')

    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <td>Id</td>
            <td>Transaction_ref_id</td>
            <td>Notification</td>
        </tr>
        </thead>
        <tbody>
        @foreach($get_notifications as $get_notification)
            <tr>
                <td>{{ $get_notification->id }}</td>
                <td>{{ $get_notification->transaction_ref_id }}</td>
                <td>{{ $get_notification->notification }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
