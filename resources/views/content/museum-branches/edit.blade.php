@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')
@section('page-script')
    <script src="{{ asset('assets/js/admin\project\project-upload-photo.js') }}"></script>
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
                  <a href="{{route('branches-list')}}">Թանգարանի մասնաճյուղեր </a>
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

            <form action="{{ route('branches-update',$data->id) }}" method="post" enctype="multipart/form-data">
              @method('put')
              <input type = "hidden" name = "museum_id" value="{{ $data->museum_id }}">
              @foreach (languages() as $lang)
                <div class="mb-3 row">
                  <label for="name-{{ $lang}}" class="col-md-2 col-form-label">Անվանում {{ $lang }}
                  <span class="required-field text-danger">*</span>
                  </label>
                    <div class="col-md-10">
                        <input class="form-control"
                              placeholder="Անվանումը {{ $lang }}"
                              value="{{ $data->translation($lang)->name ?? old("translate.$lang.name") }}"
                              name="translate[{{ $lang }}][name]"
                              id="name-{{ $lang}}" name="name"
                              />
                    </div>
                    @error("translate.$lang.name")
                        <div class="mb-3 row justify-content-end">
                            <div class="col-sm-10 text-danger fts-14">{{ $message }}
                            </div>
                        </div>
                    @enderror
                </div>
                <div class="mb-3 row">
                  <label for="address-{{ $lang}}" class="col-md-2 col-form-label">Հասցե {{ $lang }}
                  <span class="required-field text-danger">*</span>
                  </label>
                    <div class="col-md-10">
                        <input class="form-control"
                              placeholder="Հասցե {{ $lang }}"
                              value="{{$data->translation($lang)->address ?? old("translate.$lang.address") }}"
                              name="translate[{{ $lang }}][address]"
                              id="address-{{ $lang}}" name="address"
                              />
                    </div>
                    @error("translate.$lang.address")
                        <div class="mb-3 row justify-content-end">
                            <div class="col-sm-10 text-danger fts-14">{{ $message }}
                            </div>
                        </div>
                    @enderror
                </div>
              @endforeach
              <div class="mb-3 row">
                <label for="email" class="col-md-2 col-form-label">Էլեկտրոնային հասցե
                  <span class="required-field text-danger">*</span>
                </label>

                <div class="col-md-10">
                    <input class="form-control" placeholder="Էլեկտրոնային հասցե" value="{{ $data->email ?? old('email') }}"
                        id="email" name="email" />
                </div>
                @error("email")
                <div class="mb-3 row justify-content-end">
                    <div class="col-sm-10 text-danger fts-14">{{ $message }}
                    </div>
                </div>
            @enderror
              </div>
              <div class="mb-3 row">
                <label for="phone_number" class="col-md-2 col-form-label">Մասնաճյուղի հեռախոսահամար</label>
                <div class="col-md-10">
                    <input class="form-control" placeholder="Մասնաճյուղի հեռախոսահամար" value="{{$data->phone_number ?? old('phone_number') }}"
                        id="phone_number" name="phone_number" />
                </div>
                @error("phone_number")
                <div class="mb-3 row justify-content-end">
                    <div class="col-sm-10 text-danger fts-14">{{ $message }}
                    </div>
                </div>
            @enderror
              </div>
              <div class="mb-3 row">
                <label for="email" class="col-md-2 col-form-label">Հղում</label>
                <div class="col-md-10">
                    <input class="form-control" placeholder="Հղում" value="{{$data->links[0]->link ?? old('link') }}"
                        id="link" name="link" />
                </div>
                @error("link")
                <div class="mb-3 row justify-content-end">
                    <div class="col-sm-10 text-danger fts-14">{{ $message }}
                    </div>
                </div>
            @enderror
              </div>

                @foreach (languages() as $lang)
                    <div class="mb-3 row">
                        <label for="working_days-{{ $lang }}" class="col-md-2 col-form-label">Աշխատանքային օրեր {{ $lang }}
                        <span class="required-field text-danger">*</span>
                        </label>
                        <div class="col-md-10">
                            <textarea class="form-control" placeholder="Աշխատանքային օրեր {{ $lang }}"
                                id="working_days-{{ $lang }}"
                                name="translate[{{ $lang }}][working_days]">{{ $data->translation($lang)->working_days ?? old("translate.$lang.working_days") }}
                            </textarea>
                        </div>
                    </div>
                    @error("translate.$lang.working_days")
                        <div class="mb-3 row justify-content-end">
                            <div class="col-sm-10 text-danger fts-14">{{ $message }}
                            </div>
                        </div>
                    @enderror


                    <div class="mb-3 row">
                        <label for="description-{{ $lang }}" class="col-md-2 col-form-label">Նկարագիր
                            {{ $lang }}
                            <span class="required-field text-danger">*</span>
                        </label>


                        <div class="col-md-10">
                            <textarea id="description-{{ $lang }}" class="form-control" placeholder="Նկարագիր"
                                name="translate[{{ $lang }}][description]">{{ $data->translation($lang)->description ?? old("translate.$lang.description") }}</textarea>
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
                    <label for="photo" class="col-md-2 col-form-label">Մասնաճյուղի նկար
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
                              @foreach($data->images as $key => $image)
                              <div class="uploaded-image-div mx-2">
                                  <img src="{{route('get-file', ['path' => $image->path])}}" class="d-block rounded uploaded-image uploaded-photo-project">

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
    </div>


    </div>
@endsection
