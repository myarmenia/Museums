@extends('layouts/contentNavbarLayout')
@section('title', 'Գործընկերներ - Ստեղծել')

@section('content')

<h4 class="py-3 mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('partners_list')}}">Գործընկերներ</a>
            </li>
            <li class="breadcrumb-item active">Ստեղծել նոր գործընկեր</li>

        </ol>
    </nav>
</h4>

<div class="card">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="card-header">Ստեղծել նոր գործընկեր</h4>
        </div>
    </div>

    <div class="card-body">
        <form action="{{route('partners_store')}}" method="post">

            <div class="mb-3 row">
                <label for="name" class="col-md-2 col-form-label">Անուն
                  <span class="required-field text-danger">*</span>
                </label>
                <div class="col-md-10">
                    <input class="form-control"
                          placeholder="Անուն "
                          value="{{ old('name') }}"
                          id="name" name="name"
                          />
                </div>
                @error("name")
                    <div class="mb-3 row justify-content-end">
                        <div class="col-sm-10 text-danger fts-14">{{ $message }}
                        </div>
                    </div>
                @enderror
            </div>


            <div class="mb-3 row" >
                <label for="tin" class="col-md-2 col-form-label">ՀՎՀՀ
                  <span class="required-field text-danger">*</span>
                </label>

                <div class="col-md-10">
                    <input class="form-control" placeholder="ՀՎՀՀ" value="{{ old('tin') }}"
                        id="tin" name="tin" />
                </div>
                @error("tin")
                  <div class="mb-3 row justify-content-end">
                      <div class="col-sm-10 text-danger fts-14">{{ $message }}
                      </div>
                  </div>
                @enderror
            </div>

            <div class="mb-3 row" >
                <label for="phone" class="col-md-2 col-form-label">Հեռախոս
                  <span class="required-field text-danger">*</span>
                </label>

                <div class="col-md-10">
                    <input class="form-control" placeholder="Հեռախոս" value="{{ old('phone') }}"
                        id="phone" name="phone" />
                </div>
                @error("phone")
                  <div class="mb-3 row justify-content-end">
                      <div class="col-sm-10 text-danger fts-14">{{ $message }}
                      </div>
                  </div>
                @enderror
            </div>

            <div class="mb-3 row" >
                <label for="account_number" class="col-md-2 col-form-label">Հաշվեհամար
                  <span class="required-field text-danger"></span>
                </label>

                <div class="col-md-10">
                    <input class="form-control" placeholder="Հաշվեհամար" value="{{ old('account_number') }}"
                        id="account_number" name="account_number" />
                </div>
                @error("account_number")
                  <div class="mb-3 row justify-content-end">
                      <div class="col-sm-10 text-danger fts-14">{{ $message }}
                      </div>
                  </div>
                @enderror
            </div>

            <div class="mb-3 row" >
                <label for="bank" class="col-md-2 col-form-label">Բանկ
                  <span class="required-field text-danger"></span>
                </label>

                <div class="col-md-10">
                    <input class="form-control" placeholder="Բանկ" value="{{ old('bank') }}"
                        id="bank" name="bank" />
                </div>
                @error("bank")
                  <div class="mb-3 row justify-content-end">
                      <div class="col-sm-10 text-danger fts-14">{{ $message }}
                      </div>
                  </div>
                @enderror
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
