@include('layouts.navigation.navbar')

<p>

<div class="container" align="center">

 <div class="alert alert-warning" role="alert">
           <h1>Hakikisha!</h1>
        </div>

    @include('layouts.includes.session_flash')


    <div class="alert alert-info" role="alert">
        Review transaction details below, to continue press Continue, to stop, press Cancel.
    </div>


    <form class="cmxform" method="POST" action={{"/registeredcustomers/store"}}>
        @csrf
        @include('layouts.includes.session_flash')

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputEmail4">Receiver First Name</label>
                <input type="text" class="form-control" id='receiverfname' name='receiverfname'  value="{{$hakikishafname}}">
            </div>
            <div class="form-group col-md-6">
                <label for="inputPassword4">Receiver Last Name</label>
                <input type="text" class="form-control" id='receiverlname' name='receiverlname' value="{{$hakikishalname}}" >
            </div>
        </div>

        <div class="form-group">
            <label for="inputAddress">Sender Phone Number</label>
            <input type="text" class="form-control" id='senderphone' name='senderphone' value="{{$inputs1}}" >
        </div>

            <label for="inputAddress">Receiver Phone Number</label>
            <input type="text"  class="form-control" id='receiverphone' name='receiverphone' value="{{$inputs2}}" >

        <div class="form-group">
            <label for="inputAddress2">Transaction Amount Kes</label>
            <input type="text" class="form-control" id='tamount' name='tamount' value="{{$inputs3}}" >
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputCity">Transaction Cost Kes</label>
                <input type="text" class="form-control" id='tcost' name='tcost' value="{{$hakikishatcost}}">
            </div>

        </div>
        <button type="submit" class="btn btn-primary">Continue</button>
    </form>

    <p>
    <form class="cmxform" method="GET" action={{"/transactions/hakikisha/cancel"}}>
        @csrf
        <button type="submit" class="btn btn-danger">Cancel</button>
    </form>
    </p>



</div>
