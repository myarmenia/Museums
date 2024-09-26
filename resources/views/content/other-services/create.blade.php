@extends('layouts/contentNavbarLayout')
@section('title', 'Այլ ծառայություններ - Ստեղծել')

@section('content')

<h4 class="py-3 mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('other_services_list')}}">Այլ ծառայություններ</a>
            </li>
            <li class="breadcrumb-item active">Ստեղծել նոր ծառայություն</li>

        </ol>
    </nav>
</h4>

<div class="card">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="card-header">Ստեղծել նոր ծառայություն</h4>
        </div>
    </div>

    <div class="card-body">
        <form action="{{route('other_services_store')}}" method="post">

            @foreach (languages() as $lang)
              <div class="mb-3 row">
                  <label for="name-{{ $lang}}" class="col-md-2 col-form-label">Վերնագիր {{ $lang }}
                    <span class="required-field text-danger">*</span>
                  </label>
                  <div class="col-md-10">
                      <input class="form-control"
                            placeholder="Վերնագիր {{ $lang }}"
                            value="{{ old("translate.$lang.name") }}"
                            name="translate[{{ $lang }}][name]"
                            id="name-{{ $lang}}"
                            />
                  </div>
                  @error("translate.$lang.name")
                      <div class="mb-3 row justify-content-end">
                          <div class="col-sm-10 text-danger fts-14">{{ $message }}
                          </div>
                      </div>
                  @enderror
              </div>

            @endforeach

            <div class="mb-3 row" >
                <label for="price" class="col-md-2 col-form-label">Գին
                  <span class="required-field text-danger">*</span>
                </label>

                <div class="col-md-10">
                    <input class="form-control" placeholder="Գին" value="{{ old('price') }}"
                        id="price" name="price" />
                </div>
                @error("price")
                  <div class="mb-3 row justify-content-end">
                      <div class="col-sm-10 text-danger fts-14">{{ $message }}
                      </div>
                  </div>
                @enderror
            </div>

            <div class="mb-3 row">
                <label for="ticket" class="col-md-2 col-form-label"></label>
                <div class="d-flex col-md-10">
                    <div class="col-md-2 form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="ticket" name="ticket">
                        <label class="form-check-label" for="ticket">Տոմս</label>
                    </div>
                </div>
            </div>

            <div class="row justify-content-end">
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary">Ստեղծել</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
