@extends('layouts/contentNavbarLayout')

@section('page-script')
    <script src="{{ asset('assets/js/admin\project\project-upload-photo.js') }}"></script>
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/project/project.css') }}">
@endsection

@section('content')

    <h4 class="py-3 mb-4">
      <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
              <li class="breadcrumb-item">
                  <a href="{{route('banner_list')}}">Բաններ </a>
              </li>
              <li class="breadcrumb-item active">Ստեղծել բաններ</li>
          </ol>
      </nav>
  </h4>
    <div class="card">

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-header">Ստեղծել բաններ </h5>
            </div>

        </div>
        <div class="card-body">

            <form action="{{ route('banner_store') }}" method="post" enctype="multipart/form-data">


              @foreach (languages() as $lang)
              <div class="mb-3 row">
                <label for="text-{{ $lang }}" class="col-md-2 col-form-label">Բանների տեքստ {{ $lang }}
                <span class="required-field text-danger">*</span>
                </label>
                <div class="col-md-10">
                    <textarea class="form-control" placeholder="Բանների տեքստ {{ $lang }}"
                        id="working_days-{{ $lang }}"
                        name="translate[{{ $lang }}][text]">{{ old("translate.$lang.text") }}
                    </textarea>

                </div>
            </div>
            @error("translate.$lang.text")
                <div class="mb-3 mt-5 row justify-content-end">
                    <div class="col-sm-10 text-danger fts-14">{{ $message }}
                    </div>
                </div>
            @enderror

              @endforeach


                <div class="mb-3 row">
                    <label for="photo" class="col-md-2 col-form-label">Ապրանքի նկար
                    <span class="required-field text-danger">*</span>
                    </label>

                    <div class="col-md-10">
                        <div class="d-flex flex-wrap align-items-start align-items-sm-center">
                            <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                <span class="d-none d-sm-block">Ներբեռնել նկար</span>
                                <i class="bx bx-upload d-block d-sm-none"></i>
                                <input type="file" id="upload" name="photo" class="account-file-input" hidden
                                    accept="image/png, image/jpeg" />
                            </label>
                            <div class="uploaded-images-container uploaded-photo-project" id="uploadedImagesContainer">
                            </div>

                        </div>
                    </div>
                </div>
                @error('photo')
                    <div class="mb-3 row justify-content-end">
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
    </div>


    </div>
@endsection
