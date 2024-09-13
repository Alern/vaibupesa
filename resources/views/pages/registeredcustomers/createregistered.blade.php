@include('layouts.navigation.navbar')

<section class="vh-100" style="background-color: #eee;">
    <div class="container h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-lg-12 col-xl-11">
                <div class="card text-black" style="border-radius: 25px;">
                    <div class="card-body p-md-5">
                        <div class="row justify-content-center">
                            <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">

                                <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Transact | Send Money</p>

                                <p class="text-center h3 fw-bold mb-5 mx-1 mx-md-4 mt-4">Wallet: Kes.{{$sender_query_balance}}</p>

                                <form class="cmxform" method="POST"  action={{"/rtransactions/hakikisha/validate"}}>
                                    @csrf

                                    @include('layouts.includes.session_flash')

                                    <div class="d-flex flex-row align-items-center mb-4">
                                        <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                        <div data-mdb-input-init class="form-outline flex-fill mb-0">
                                            <label class="form-label" for="form3Example1c">Receiver Customer</label>
                                            <input type="number" id="receiverMsisdn" name='receiverMsisdn'
                                                   value="{{old('receiverMsisdn')}}" class="form-control @error('receiverMsisdn') is-invalid @enderror"/>
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('receiverMsisdn') }}.</strong>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-row align-items-center mb-4">
                                        <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                        <div data-mdb-input-init class="form-outline flex-fill mb-0">
                                            <label class="form-label" for="form3Example1c">Amount</label>
                                            <input type="number" id="amount" name='amount' value="{{old('amount')}}"
                                                   class="form-control @error('amount') is-invalid @enderror"/>
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('amount') }}.</strong>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Pin') }}</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>


                                    <button type="submit"
                                            class="btn btn-primary btn-sm"
                                            id="submit" value="submit">
                                        Submit
                                    </button>


                                </form>
                            </div>

                            <div
                                class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">
                                <img
                                    src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-registration/draw1.webp"
                                    class="img-fluid" alt="Sample image">
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
