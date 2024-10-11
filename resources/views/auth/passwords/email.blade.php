@extends('layouts/blankLayout')

@section('title', 'Forgot Password Basic - Pages')

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
                            <a href="{{ url('/') }}" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">
                                    @include('_partials.macros',["width"=>25,"withbg"=>'var(--bs-primary)'])
                                </span>
                                {{-- <span class="app-brand-text demo text-body fw-bold">{{ config('variables.templateName') }}</span> --}}
                            </a>
                        </div>

                        <!-- /Logo -->
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if (session('email'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('email') }}
                        </div>
                    @endif
                        <h6 class="mb-2">Գաղտնաբառի վերականգնում 🔒</h6>
                        <p class="mb-4">Մուտքագրեք Ձեր էլ․հասցեն </p>
                        <form id="formAuthentication" class="mb-3" action="{{ route('password.email') }}" method="post">
                            @csrf
                            <div class="mb-3">

                                <label for="email" class="form-label">Էլ․ հասցե</label>
                                <input type="text" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" placeholder="Մուտքագրեք Ձեր էլ․ հասցեն" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <button class="btn btn-primary d-grid w-100">Ուղարկեք վերականգնման հղումը</button>

                        </form>
                        <div class="text-center">
                            <a href="{{ url('auth/login-basic') }}"
                                class="d-flex align-items-center justify-content-center">
                                <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                                Մուտք
                            </a>
                        </div>
                    </div>
                </div>
                <!-- /Forgot Password -->
            </div>
        </div>
    </div>
@endsection
