<form method="POST" action="{{ route('/registeredcustomers/store') }}">
    @csrf
    <input type="text" name="msisdn" required autofocus>
    <input type="password" name="pin" required>
    <button type="submit">Login</button>
</form>
