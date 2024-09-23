@include('layouts.navigation.nokycnavbar')

    <div class="container">
        <div class="row">
            <div class="col-md-12">

                @if (session('status'))
                    <h6 class="alert alert-success">{{ session('status') }}</h6>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h4>Update Customer Balance
                            <a href="{{ url('/admin/dashboard') }}" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">

                        <form action="{{ url('/updateBal/'.$customer->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group mb-3">
                                <label for="">Customer Balance</label>
                                <input type="number" name="topupamnt" value="{{$customer->topupamnt}}" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-primary">Update Balance</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
