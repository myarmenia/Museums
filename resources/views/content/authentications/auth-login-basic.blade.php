@extends('layouts/blankLayout')

@section('title', 'Login Basic - Pages')

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
@endsection

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <!-- Register -->
      <div class="card">

        <div class="card-body">

          <!-- Logo -->
          <div class="app-brand justify-content-center">
            {{-- <a href="{{url('/')}}" class="app-brand-link gap-2">
              <span class="app-brand-logo demo">@include('_partials.macros',["width"=>25,"withbg"=>'var(--bs-primary)'])</span>
              <span class="app-brand-text demo text-body fw-bold">{{config('variables.templateName')}}</span>
            </a> --}}
          </div>
          <!-- /Logo -->


          <form id="formAuthentication" class="mb-3" action="{{route('login')}}" method="post">
            <div class="mb-3">
              <label for="email" class="form-label">Էլ․հասցե</label>
              <input type="text" class="form-control" id="email" name="email" placeholder="Մուտքագրեք Ձեր Էլ․հասցեն" autofocus>

            </div>
            @error('email')
              <div class="mb-3 row justify-content-start">
                  <div class="col-sm-10 text-danger fts-14">{{$message}}
                  </div>
              </div>
            @enderror

            <div class="mb-3 form-password-toggle">
              <div class="d-flex justify-content-between">
                <label class="form-label" for="password">Գաղտնաբառ</label>

                @if (Route::has('password.request'))
                <a class="btn btn-link" href="{{ route('password.request') }}">
                    {{ __('Մոռացել եք գաղտնաբառը?') }}
                </a>
            @endif
              </div>

              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
              @error('password')
                <div class="mb-3 row justify-content-start">
                    <div class="col-sm-10 text-danger fts-14">{{$message}}
                    </div>
                </div>
              @enderror
            </div>

            <div class="mb-3">
              <button class="btn btn-primary d-grid w-100" type="submit">Մուտք</button>
            </div>
          </form>
          @if(session('success'))
          <div class="text-danger">{{ session('success') }}</div>
        @endif

        </div>
      </div>
    </div>
    <!-- /Register -->
  </div>
</div>
</div>
@endsection
