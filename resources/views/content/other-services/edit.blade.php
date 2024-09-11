@extends('layouts/contentNavbarLayout')
@section('title', 'Այլ ծառայություններ - Փոփոխել')

@section('content')

<h4 class="py-3 mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('other_services_list')}}">Այլ ծառայություններ</a>
            </li>
            <li class="breadcrumb-item active">Փոփոխել ծառայությունը</li>
        </ol>
    </nav>
</h4>

<div class="card">

    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="card-header">Փոփոխել ծառայությունը</h4>
        </div>
    </div>
    <div class="card-body">

        <form action="{{route('other_services_update', $other_service->id)}}" method="post">
            @method('put')

           @foreach (languages() as $lang)
                  <div class="mb-3 row">
                      <label for="name-{{ $lang }}" class="col-md-2 col-form-label">Անվանում {{ $lang }} <span class="required-field text-danger">*</span></label>

                      <div class="col-md-10">
                          <input class="form-control" placeholder="Անվանում" value="{{ $other_service->translation($lang)->name }}"
                              id="name-{{ $lang }}" name="translate[{{ $lang }}][name]" />
                      </div>
                  </div>
                  @error("translate.$lang.name")
                      <div class="mb-3 row justify-content-end">
                          <div class="col-sm-10 text-danger fts-14">{{ $message }}
                          </div>
                      </div>
                  @enderror
            @endforeach

            <div class="mb-3 row">
                <label for="price" class="col-md-2 col-form-label">Գին <span class="required-field text-danger">*</span></label>

                <div class="col-md-10">
                    <input class="form-control" placeholder="Գին" value="{{ $other_service->price }}" id="price" name="price" />
                </div>
            </div>
            @error("price")
                <div class="mb-3 row justify-content-end">
                    <div class="col-sm-10 text-danger fts-14">{{ $message }}</div>
                </div>
            @enderror

            <div class="row justify-content-end">
              <div class="col-sm-10">
                  <button type="submit" class="btn btn-primary">Պահպանել</button>
              </div>
          </div>

        </form>
    </div>
</div>

@endsection
