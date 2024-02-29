@extends('layouts/contentNavbarLayout')

@section('page-script')
    <script src="{{ asset('assets/js/admin\project\project-upload-photo.js') }}"></script>
    <script src="{{ asset('assets/js/delete-item.js') }}"></script>
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
                  <a href="{{route('news')}}">Նորություններ </a>
              </li>
              <li class="breadcrumb-item active">Խմբագրել</li>
          </ol>
      </nav>
  </h4>
    <div class="card">

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-header">Խմբագրել նորությունը</h5>
            </div>

        </div>
        <div class="card-body">

            <form action="{{ route('news-update',$news->id) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method("PUT")
                  @foreach (languages() as $lang)
                      <div class="mb-3 row">
                          <label for="lang-{{ $lang }}" class="col-md-2 col-form-label">Վերնագիր
                              {{ $lang }}</label>

                          <div class="col-md-10">
                              <input class="form-control" placeholder="Заголовок" value="{{ $news->translation($lang)->title }}"
                                  id="title-{{ $lang }}"
                                  name="translate[{{ $lang }}][title]"
                                  />
                          </div>
                      </div>
                      @error("translate.$lang.title")
                          <div class="mb-3 row justify-content-end">
                              <div class="col-sm-10 text-danger fts-14">{{ $message }}
                              </div>
                          </div>
                      @enderror
                      <div class="mb-3 row">
                          <label for="description-{{ $lang }}" class="col-md-2 col-form-label">Տեքստ
                              {{ $lang }}</label>

                          <div class="col-md-10">
                              <textarea id="description-{{ $lang }}" class="form-control" placeholder="Տեքստ"
                                  name="translate[{{ $lang }}][description]"
                                  >{{ $news->translation($lang)->description }}</textarea>
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
                    <label for="photo" class="col-md-2 col-form-label">Նորության նկար</label>
                    <div class="col-md-10">
                        <div class="d-flex flex-wrap align-items-start align-items-sm-center">
                            <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                <span class="d-none d-sm-block">Ներբեռնել նկար</span>
                                <i class="bx bx-upload d-block d-sm-none"></i>
                                <input type="file" id="upload" name="photo" class="account-file-input" hidden
                                    accept="image/png, image/jpeg" />
                            </label>
                            <div class="uploaded-images-container  uploaded-photo-project" id="uploadedImagesContainer">

                              @foreach($news->images as $key => $image)
                                <div class="uploaded-image-div mx-2">
                                    <img src="{{route('get-file', ['path' => $image->path])}}" class="d-block rounded uploaded-image uploaded-photo-project">

                                </div>
                              @endforeach
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
