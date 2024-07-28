@extends('layouts/contentNavbarLayout')


@section('page-script')
    <script src="{{ asset('assets/js/admin\news\index.js') }}"></script>
    <script src="{{ asset('assets/js/admin\event\index.js') }}"></script>
@endsection


@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/project/project.css') }}">
@endsection

@section('content')
    @include('includes.alert')

    <h4 class="py-3 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('event_list') }}">Միջոցառումներ </a>
                </li>
                <li class="breadcrumb-item active">Խմբագրել</li>
            </ol>
        </nav>
    </h4>
    <div class="card">

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-header">Խմբագրել</h5>
            </div>

        </div>
        <div class="card-body">
            {{-- {{dd($data)}} --}}

            <form action="{{ route('event_update', $data->id) }}" method="POST" enctype="multipart/form-data">
                @method('put')
                <input type = "hidden" name = "museum_id" value="{{ $data->museum_id }}">

                @foreach (languages() as $lang)
                    <div class="mb-3 row">
                        <label for="name-{{ $lang }}" class="col-md-2 col-form-label">Անվանում {{ $lang }}
                            <span class="required-field text-danger">*</span>
                        </label>
                        <div class="col-md-10">
                            <input class="form-control" placeholder="Անվանումը {{ $lang }}"
                                value="{{ $data->translation($lang)->name ?? old("translate.$lang.name") }}"
                                name="translate[{{ $lang }}][name]" id="name-{{ $lang }}"
                                name="name" />
                        </div>
                        @error("translate.$lang.name")
                            <div class="mb-3 row justify-content-end">
                                <div class="col-sm-10 text-danger fts-14">{{ $message }}
                                </div>
                            </div>
                        @enderror
                    </div>
                @endforeach
                @foreach (languages() as $lang)
                    <div class="mb-3 row">
                        <label for="name-{{ $lang }}" class="col-md-2 col-form-label">Նկարագիր {{ $lang }}
                            <span class="required-field text-danger">*</span>
                        </label>
                        <div class="col-md-10">

                            <textarea id="description-{{ $lang }}" class="form-control" placeholder="Նկարագիր"
                                name="translate[{{ $lang }}][description]">{{ $data->translation($lang)->description ?? old("translate.$lang.description") }}</textarea>
                        </div>
                        @error("translate.$lang.description")
                            <div class="mb-3 row justify-content-end">
                                <div class="col-sm-10 text-danger fts-14">{{ $message }}
                                </div>
                            </div>
                        @enderror
                    </div>
                @endforeach
                <div class="mb-3 row">
                    <label for="phone_number" class="col-md-2 col-form-label">Միջոցառման սկիզբ
                        <span class="required-field text-danger">*</span>
                    </label>
                    <div class="col-md-10">
                        <input class="form-control" type="date" placeholder="Միջոցառման սկիզբ"
                            value="{{ $data->start_date ?? old('start_date') }}" id="start_date" name="start_date" />
                    </div>
                    @error('start_date')
                        <div class="mb-3 row justify-content-end">
                            <div class="col-sm-10 text-danger fts-14">{{ $message }}
                            </div>
                        </div>
                    @enderror
                </div>

                <div class="mb-3 row">
                    <label for="phone_number" class="col-md-2 col-form-label">Միջոցառման ավարտ
                        <span class="required-field text-danger">*</span>
                    </label>
                    <div class="col-md-10">
                        <input class="form-control" type="date" placeholder=""
                            value="{{ $data->end_date ?? old('end_date') }}" id="end_date" name="end_date" />
                    </div>
                    @error('end_date')
                        <div class="mb-3 row justify-content-end">
                            <div class="col-sm-10 text-danger fts-14">{{ $message }}
                            </div>
                        </div>
                    @enderror
                </div>


                <div class="mb-3 row">
                    <label for="phone_number" class="col-md-2 col-form-label">Տոմսերի առավելագույն քանակ մեկ օրվա համար
                        <span class="required-field text-danger">*</span>
                    </label>
                    <div class="col-md-10">
                        <input class="form-control" placeholder="Տոմսերի առավելագույն քանակ մեկ օրվա համար"
                            value="{{ $data->visitors_quantity_limitation ?? old('visitors_quantity_limitation') }}"
                            id="visitors_quantity_limitation" name="visitors_quantity_limitation" />
                    </div>
                    @error('visitors_quantity_limitation')
                        <div class="mb-3 row justify-content-end">
                            <div class="col-sm-10 text-danger fts-14">{{ $message }}
                            </div>
                        </div>
                    @enderror
                </div>
                <div class="mb-3 row">
                    <label for="email" class="col-md-2 col-form-label">Գին
                        <span class="required-field text-danger">*</span>
                    </label>

                    <div class="col-md-10">
                        <input class="form-control" placeholder="Գինը" value="{{ $data->price ?? old('price') }}"
                            id="price" name="price" />
                    </div>
                    @error('price')
                        <div class="mb-3 row justify-content-end">
                            <div class="col-sm-10 text-danger fts-14">{{ $message }}
                            </div>
                        </div>
                    @enderror
                </div>

                <div class="mb-3 row">
                    <label for="photo" class="col-md-2 col-form-label">Ապրանքի նկար
                        <span class="required-field text-danger">*</span>
                    </label>

                    <div class="col-md-10">
                        <div>Նկարի նախընտրելի չափսերն են 400X270</div>
                        <div class="d-flex flex-wrap align-items-start align-items-sm-center">
                            <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                <span class="d-none d-sm-block">Ներբեռնել նկար</span>
                                <i class="bx bx-upload d-block d-sm-none"></i>
                                <input type="file" id="upload" name="photo" class="account-file-input" hidden
                                    accept="image/png, image/jpeg" />
                            </label>
                            <div class="uploaded-images-container uploaded-photo-project" id="uploadedImagesContainer">
                                @foreach ($data->images as $key => $image)
                                    <div class="uploaded-image-div mx-2">
                                        <img src="{{ route('get-file', ['path' => $image->path]) }}"
                                            class="d-block rounded uploaded-image uploaded-photo-project">

                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>
                @error('photo')
                    <div class="mb-3 mt-5 row justify-content-end">
                        <div class="col-sm-10 text-danger fts-14">{{ $message }}
                        </div>
                    </div>
                @enderror
                <div class="mt-5 row justify-content-end">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">Պահպանել</button>
                    </div>
                </div>
              </div>

        </form>



              <x-edit-event-config :data="$data"  ></x-edit-event-config>






   {{-- ============Միջոցառման օրերի կարգավորումներ============= --}}
        <div class="m-3 row">
          <label for="email" class="col-form-label">Միջոցառման օրերի կարգավորումներ
              <button id="add_event_config" data-id="{{ $data->id }}" data-conf-count='0' class="btn btn-primary mx-3">+</button>
          </label>
        </div>

    </div>
    {{-- =============Միջոցառման օրերի կարգավորումներ sections=============== --}}
    <div class="card my-2"  id="config_div" style="display:none">
      <form
      id="submit_event_config"
       method="POST"
      >
        @csrf

        <div class="card-body" id="event_config"></div>


        <div class="row justify-content-end" >
          <div class="col-sm-10 my-3"  >
              <button type="submit" class="btn btn-primary">Պահպանել</button>
          </div>
        </div>
      </form>
    </div>
@endsection
<x-modal-delete></x-modal-delete>
