@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')

@section('content')

<h4 class="py-3 mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('users.index')}}">Կրթական ծրագրեր</a>
            </li>
            <li class="breadcrumb-item active">Ստեղծել նոր ծրագիր</li>

        </ol>
    </nav>
</h4>

<div class="card">

    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="card-header">Ստեղծել նոր ծրագիր</h4>
        </div>

    </div>
    <div class="card-body">

        <form action="{{route('users.store')}}" method="post">

            <div class="mb-3 row">
                <label for="name" class="col-md-2 col-form-label">Անուն</label>
                <div class="col-md-10">
                    <input class="form-control" type="text" placeholder="Անուն" id="name" name="name" value="{{old('name')}}">
                </div>
            </div>
            @error('name')
            <div class="mb-3 row justify-content-end">
                <div class="col-sm-10 text-danger fts-14">{{$message}}
                </div>
            </div>
            @enderror


            @foreach (languages() as $lang)
                  <div class="mb-3 row">
                      <label for="lang-{{ $lang }}" class="col-md-2 col-form-label">Վերնագիր {{ $lang }}</label>

                      <div class="col-md-10">
                          <input class="form-control" placeholder="Վերնագիր" value="{{ old("translate.$lang.name") }}"
                              id="title-{{ $lang }}" name="translate[{{ $lang }}][title]" />
                      </div>
                  </div>
                  @error("translate.$lang.title")
                      <div class="mb-3 row justify-content-end">
                          <div class="col-sm-10 text-danger fts-14">{{ $message }}
                          </div>
                      </div>
                  @enderror

                  <div class="mb-3 row">
                        <label for="description-{{ $lang }}" class="col-md-2 col-form-label">Նկարագրություն {{ $lang }}</label>

                        <div class="col-md-10">
                            <textarea id="description-{{ $lang }}" class="form-control" placeholder="Նկարագրություն"
                                name="translate[{{ $lang }}][description]">{{ old("translate.$lang.description") }}</textarea>
                        </div>
                  </div>
                  @error("translate.$lang.description")
                      <div class="mb-3 row justify-content-end">
                          <div class="col-sm-10 text-danger fts-14">{{ $message }}
                          </div>
                      </div>
                  @enderror

            @endforeach

            <div class="mb-3 row">
                <label for="price" class="col-md-2 col-form-label">Գին</label>

                <div class="col-md-10">
                    <input class="form-control" placeholder="Գին" value="{{ old("price") }}" id="price" name="price" />
                </div>
            </div>
            @error("price")
                <div class="mb-3 row justify-content-end">
                    <div class="col-sm-10 text-danger fts-14">{{ $message }}
                    </div>
                </div>
            @enderror

            <div class="mb-3 row">
                <label for="max_quantity" class="col-md-2 col-form-label">Այցելուների առավելագույն քանակ</label>

                <div class="col-md-10">
                    <input class="form-control" placeholder="Այցելուների առավելագույն քանակ" value="{{ old("price") }}" id="max_quantity" name="max_quantity" />
                </div>
            </div>
            @error("max_quantity")
                <div class="mb-3 row justify-content-end">
                    <div class="col-sm-10 text-danger fts-14">{{ $message }}
                    </div>
                </div>
            @enderror

            <div class="mb-3 row">
                <label for="min_quantity" class="col-md-2 col-form-label">Այցելուների նվազագույն քանակ</label>

                <div class="col-md-10">
                    <input class="form-control" placeholder="Այցելուների նվազագույն քանակ" value="{{ old("price") }}" id="min_quantity" name="min_quantity" />
                </div>
            </div>
            @error("min_quantity")
                <div class="mb-3 row justify-content-end">
                    <div class="col-sm-10 text-danger fts-14">{{ $message }}
                    </div>
                </div>
            @enderror

        </form>
    </div>


</div>


@endsection
