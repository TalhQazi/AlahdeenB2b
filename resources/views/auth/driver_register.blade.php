@extends('layouts.driver')

@section('content')
    <div class="wrapper ">
        <div class="content">
            <div class="container-fluid">
                <div class="row login_row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <div class="text-center mb-1"><img src="{{ asset(setting('logo_section_logo')) }}" width="160" ></div>
                      <div class="card">
                        <div class="card-header card-header-warning">

                            <h4 class="card-title">Enter your Login Details</h4>
                        </div>
                        <div class="card-body">
                          <form method="post" action="{{ route('front.login') }}">
                            @csrf
                            <div class="form-group">
                              <label>Enter Email</label>
                              <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" required="">
                              @error('email')
                                <div class="error pl-2">{{ $message }}</div>
                              @enderror
                            </div>
                            <div class="form-group">
                              <label>Enter Password</label>
                              <input type="password" name="password" class="form-control @error('email') is-invalid @enderror" required="">
                              @error('password')
                                <div class="alert alert-danger">{{ $message }}</div>
                              @enderror
                            </div>
                            <button class="btn btn-primary btn-block bold">Sign In</button>
                          </form>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4"></div>
                </div>
            </div>
        </div>
    </div>
@endsection