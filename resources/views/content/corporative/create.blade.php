@extends('layouts/contentNavbarLayout')
@section('content')

<h4 class="py-3 mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('corporative')}}">Կորպորատիվ</a>
            </li>
            <li class="breadcrumb-item active">Ստեղծել նոր կորպորատիվ</li>
        </ol>
    </nav>
</h4>

<div class="card">

    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="card-header">Ստեղծել նոր կորպորատիվ</h4>
        </div>
    </div>
    <div class="card-body">

        <form action="{{route('corporative.add')}}" method="post"  enctype="multipart/form-data">
            <div class="mb-3 row">
                <label for="name" class="col-md-2 col-form-label">Անուն <span class="required-field">*</span></label>
                <div class="col-md-10">
                    <input class="form-control" type="text" placeholder="Կազմակերպության անունը" id="name" name="name" value="{{old('name')}}">
                </div>
            </div>
            @error('name')
            <div class="mb-3 row justify-content-end">
                <div class="col-sm-10 text-danger fts-14">{{$message}}
                </div>
            </div>
            @enderror

            <div class="mb-3 row">
                <label for="tin" class="col-md-2 col-form-label">ՀՎՀՀ <span class="required-field">*</span></label>
                <div class="col-md-10">
                    <input class="form-control" type="text" placeholder="Հարկ վճարողի հաշվառման համար" id="tin" name="tin" value="{{old('tin')}}">
                </div>
            </div>
            @error('tin')
            <div class="mb-3 row justify-content-end">
                <div class="col-sm-10 text-danger fts-14">{{$message}}
                </div>
            </div>
            @enderror

            <div class="mb-3 row">
                <label for="email" class="col-md-2 col-form-label">Էլ․ հասցե</label>
                <div class="col-md-10">
                    <input class="form-control" type="search" placeholder="Էլ․ հասցե" id="email" name="email" value="{{old('email')}}">
                </div>
            </div>
            @error('email')
            <div class="mb-3 row justify-content-end">
                <div class="col-sm-10 text-danger fts-14">{{$message}}
                </div>
            </div>
            @enderror

            <div class="mb-3 row">
                <label for="contract_number" class="col-md-2 col-form-label">Պայմանագրի համար</label>
                <div class="col-md-10">
                    <input class="form-control" type="text" placeholder="Պայմանագրի համար" id="contract_number" name="contract_number" value="{{old('contract_number')}}">
                </div>
            </div>

            <div class="mb-3 row">
                <label for="tickets_count" class="col-md-2 col-form-label">Տոմսերի քանակ<span class="required-field">*</span></label>
                <div class="col-md-10">
                    <input class="form-control" type="search" placeholder="Տոմսերի քանակ" id="tickets_count" name="tickets_count" value="{{old('tickets_count')}}">
                </div>
            </div>
            @error('tickets_count')
            <div class="mb-3 row justify-content-end">
                <div class="col-sm-10 text-danger fts-14">{{$message}}
                </div>
            </div>
            @enderror

            <div class="mb-3 row">
                <label for="price" class="col-md-2 col-form-label">Գին<span class="required-field">*</span></label>
                <div class="col-md-10">
                    <input class="form-control" type="search" placeholder="Ընդհանուր գին" id="price" name="price" value="{{old('price')}}">
                </div>
            </div>
            @error('price')
            <div class="mb-3 row justify-content-end">
                <div class="col-sm-10 text-danger fts-14">{{$message}}
                </div>
            </div>
            @enderror

            <div class="mb-3 row">
                <label for="file" class="col-md-2 col-form-label">Ֆալյ</label>
                <div class="col-md-10">
                    <input type="file" class="form-control" name="file" aria-label="Upload">
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
