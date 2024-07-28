@extends('layouts/contentNavbarLayout')

@section('title', 'Կորպորատիվ - Փոփոխում')

@section('page-script')
    <script src="{{ asset('assets/js/corporative/delete-file.js') }}"></script>
@endsection

@section('content')
    @include('includes.alert')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Կորպորատիվ /</span> Փոփոխել կորպորատիվը
    </h4>
    <div class="card">

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-header">Փոփոխել կորպորատիվը</h5>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('corporative.edit', $data->id) }}" method="post" enctype="multipart/form-data">
                <div class="mb-3 row">
                    <label for="name" class="col-md-2 col-form-label">Անուն <span
                            class="required-field">*</span></label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" placeholder="Կազմակերպության անունը" id="name"
                            name="name" value="{{ $data['name'] }}">
                    </div>
                </div>
                @error('name')
                    <div class="mb-3 row justify-content-end">
                        <div class="col-sm-10 text-danger fts-14">{{ $message }}
                        </div>
                    </div>
                @enderror

                <div class="mb-3 row">
                    <label for="tin" class="col-md-2 col-form-label">ՀՎՀՀ <span class="required-field">*</span></label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" placeholder="Հարկ վճարողի հաշվառման համար" id="tin"
                            name="tin" value="{{ $data['tin'] }}">
                    </div>
                </div>
                @error('tin')
                    <div class="mb-3 row justify-content-end">
                        <div class="col-sm-10 text-danger fts-14">{{ $message }}
                        </div>
                    </div>
                @enderror

                <div class="mb-3 row">
                    <label for="email" class="col-md-2 col-form-label">Էլ․ հասցե<span
                            class="required-field">*</span></label>
                    <div class="col-md-10">
                        <input type="email" class="form-control" type="search" placeholder="Էլ․ հասցե" id="email"
                            name="email" value="{{ $data['email'] }}">
                    </div>
                </div>
                @error('email')
                    <div class="mb-3 row justify-content-end">
                        <div class="col-sm-10 text-danger fts-14">{{ $message }}
                        </div>
                    </div>
                @enderror

                <div class="mb-3 row">
                    <label for="contract_number" class="col-md-2 col-form-label">Պայմանագրի համար</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" placeholder="Պայմանագրի համար" id="contract_number"
                            name="contract_number" value="{{ $data['contract_number'] }}">
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="tickets_count" class="col-md-2 col-form-label">Տոմսերի քանակ<span
                            class="required-field">*</span></label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" placeholder="Տոմսերի քանակ" id="tickets_count"
                            name="tickets_count" value="{{ $data['tickets_count'] }}">
                    </div>
                </div>
                @error('tickets_count')
                    <div class="mb-3 row justify-content-end">
                        <div class="col-sm-10 text-danger fts-14">{{ $message }}
                        </div>
                    </div>
                @enderror

                <div class="mb-3 row">
                    <label for="price" class="col-md-2 col-form-label">Գին<span class="required-field">*</span></label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" placeholder="Ընդհանուր գին" id="price" name="price"
                            value="{{ $data['price'] }}">
                    </div>
                </div>
                @error('price')
                    <div class="mb-3 row justify-content-end">
                        <div class="col-sm-10 text-danger fts-14">{{ $message }}
                        </div>
                    </div>
                @enderror

                <div class="d-flex">
                    <label for="file" class="col-md-2 col-form-label">Ֆայլ</label>
                    <div class="d-flex w-100">
                        <div id='showed-file'>
                            @if ($data['file_path'])
                                <div>
                                    <div>
                                        <a class="btn btn-primary me-2"
                                            href="{{ route('get-file', ['path' => $data['file_path']]) }}" target="_blank">
                                            Դիտել ֆայլը
                                        </a>
                                    </div>
                                    <button type="button" id='delete-file' data-attr="{{ $data['id'] }}" class="btn btn-outline-danger btn-sm mt-2 delete_item"
                                        ><i class="bx bx-trash me-1"></i>
                                        Ջնջել
                                    </button>
                                </div>
                            @endif
                        </div>
                        <div>
                            <input type="file" class="form-control" name="file" aria-label="Upload">
                        </div>
                    </div>
                </div>

                <div class="row justify-content-end mt-2">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">Փոփոխել</button>
                    </div>
                </div>

            </form>
        </div>
    </div>


    </div>


@endsection
