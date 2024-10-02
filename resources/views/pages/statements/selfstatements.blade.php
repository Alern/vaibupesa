@include('layouts.navigation.navbar')

<p>

<div class="container" align="center">

    <div class="alert alert-warning" role="alert">
        <h1>Self Statement.</h1>
    </div>

    @include('layouts.includes.session_flash')


    <div class="alert alert-info" role="alert">
        Input Dates to Search for Statement.
    </div>


    <form class="cmxform" method="POST" action={{"/registered/selfstatements/search"}}>
        @csrf/registered/selfstatements/search
        @include('layouts.includes.session_flash')

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

