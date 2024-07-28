@extends('layouts/contentNavbarLayout')

@section('content')

<h4 class="py-3 mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('educational_programs_list')}}">Կրթական ծրագրեր</a>
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

        <form action="{{route('educational_programs_store')}}" method="post">

           @foreach (languages() as $lang)
                  <div class="mb-3 row">
                      <label for="name-{{ $lang }}" class="col-md-2 col-form-label">Վերնագիր {{ $lang }} <span class="required-field text-danger">*</span></label>

                      <div class="col-md-10">
                          <input class="form-control" placeholder="Վերնագիր" value="{{ old("translate.$lang.name") }}"
                              id="name-{{ $lang }}" name="translate[{{ $lang }}][name]" />
                      </div>
                  </div>
                  @error("translate.$lang.name")
                      <div class="mb-3 row justify-content-end">
                          <div class="col-sm-10 text-danger fts-14">{{ $message }}
                          </div>
                      </div>
                  @enderror

                  <div class="mb-3 row">
                        <label for="description-{{ $lang }}" class="col-md-2 col-form-label">Նկարագրություն {{ $lang }} <span class="required-field text-danger">*</span></label>

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
                <label for="price" class="col-md-2 col-form-label">Գին <span class="required-field text-danger">*</span></label>

                <div class="col-md-10">
                    <input class="form-control" placeholder="Գին" value="{{ old("price") }}" id="price" name="price" />
                </div>
            </div>
            @error("price")
                <div class="mb-3 row justify-content-end">
                    <div class="col-sm-10 text-danger fts-14">{{ $message }}</div>
                </div>
            @enderror

            <div class="mb-3 row">
                <label for="min_quantity" class="col-md-2 col-form-label">Այցելուների նվազագույն քանակ <span class="required-field text-danger">*</span></label>

                <div class="col-md-10">
                    <input class="form-control" placeholder="Այցելուների նվազագույն քանակ" value="{{ old("min_quantity") }}" id="min_quantity" name="min_quantity" />
                </div>
            </div>
            @error("min_quantity")
                <div class="mb-3 row justify-content-end">
                    <div class="col-sm-10 text-danger fts-14">{{ $message }} </div>
                </div>
            @enderror

            <div class="mb-3 row">
                <label for="max_quantity" class="col-md-2 col-form-label">Այցելուների առավելագույն քանակ <span class="required-field text-danger">*</span></label>

                <div class="col-md-10">
                    <input class="form-control" placeholder="Այցելուների առավելագույն քանակ" value="{{ old("max_quantity") }}" id="max_quantity" name="max_quantity" />
                </div>
            </div>
            @error("max_quantity")
                <div class="mb-3 row justify-content-end">
                    <div class="col-sm-10 text-danger fts-14">{{ $message }} </div>
                </div>
            @enderror

            <div class="row justify-content-end">
              <div class="col-sm-10">
                  <button type="submit" class="btn btn-primary">Ստեղծել</button>

              </div>
          </div>

        </form>
    </div>


</div>


@endsection
