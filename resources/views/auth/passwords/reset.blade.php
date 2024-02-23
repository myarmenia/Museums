@extends('layouts/blankLayout')

@section('title', 'REset Password Basic - Pages')

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}">
@endsection

@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">

                <!-- Forgot Password -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            {{-- <a href="{{url('/')}}" class="app-brand-link gap-2">
              <span class="app-brand-logo demo">@include('_partials.macros',["width"=>25,"withbg"=>'var(--bs-primary)'])</span>
              <span class="app-brand-text demo text-body fw-bold">{{ config('variables.templateName') }}</span>
            </a> --}}
                        </div>
                        <!-- /Logo -->

                        <p class="mb-4">Գաղտնաբառի փոփոխում</p>
                        <form id="formAuthentication" class="mb-3" action="{{ route('password.update') }}" method="post">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="mb-3">

                                <label for="email" class="form-label">Էլ․հասցե</label>
                                <input type="text" class="form-control" id="email" name="email"
                                    value="{{ $email ?? old('email') }}" autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('Գաղտնաբառ') }}</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    name="password" autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password-confirm" class="form-label">{{ __('Հաստատել գաղտնաբառը') }}</label>
                                <input type="password"
                                    class="form-control  @error('password_confirmation') is-invalid @enderror"
                                    name="password_confirmation" autocomplete="new-password">
                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                    </div>


                    <button class="btn btn-primary d-grid w-100">Փոխել գաղտնաբառը</button>

                    </form>

                </div>
            </div>
            <!-- /Forgot Password -->
        </div>
    </div>
    </div>
@endsection
