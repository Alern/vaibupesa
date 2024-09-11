@include('layouts.navigation.navbar')

<p>

<div class="container" align="center">

    <div class="alert alert-warning" role="alert">
        <h1>Statements</h1>
    </div>

    @include('layouts.includes.session_flash')


    <div class="alert alert-info" role="alert">
        Input User Details to search for statement.
    </div>


    <form class="cmxform" method="POST" action={{"/registered/statements/search"}}>
        @csrf
        @include('layouts.includes.session_flash')

        <div class="form-group">
            <label for="inputAddress">Phone Number</label>
            <input type="number" class="form-control" id='phone' name='phone' >
        </div>

        <div class="form-group">
            <label for="inputAddress">Start Date</label>
            <input type="date"  class="form-control" id='startdate' name='startdate' >
        </div>

        <div class="form-group">
            <label for="inputAddress">End Date</label>
            <input type="date"  class="form-control" id='enddate' name='enddate' >
        </div>

        <button type="submit" class="btn btn-primary">Generate</button>
    </form>

    <p>

    </p>



</div>

