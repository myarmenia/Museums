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
                <a href="{{route('product-list')}}">Ապրանքների ցուցակ </a>
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

            <form action="{{ route('product-update',$data->id) }}" method="POST" enctype="multipart/form-data">
              @method('put')
              <input type = "hidden" name = "museum_id" value="{{ $data->museum_id }}">
              <div class="mb-3 row">
                <label for="region" class="col-md-2 col-form-label"> Կատեգորիա <span class="required-field">*</span></label>
                <div class="col-md-10">
                    <select id="defaultSelect" name="product_category_id" class="form-select">
                        <option value="">Ընտրեք Կատեգորիան</option>
                        @foreach ($category as $item)
                        @if ($data->product_category_id == $item->id)
                              <option value="{{ $item->id}}" selected>{{ __('product-categories.' . $item->key) }}</option>

                        @else
                              <option value="{{ $item->id }}">{{ __('product-categories.' . $item->key) }}</option>

                        @endif

                        @endforeach
                    </select>
                    @error('product_category_id')
                        <div class="justify-content-end">
                            <div class="col-sm-10 text-danger fts-14">{{ $message }}
                            </div>
                        </div>
                    @enderror
                </div>
              </div>
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

              @endforeach
              <div class="mb-3 row">
                <label for="email" class="col-md-2 col-form-label">Գին
                  <span class="required-field text-danger">*</span>
                </label>

                <div class="col-md-10">
                    <input class="form-control" placeholder="Գինը" value="{{ $data->price ?? old('price') }}"
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
                <label for="phone_number" class="col-md-2 col-form-label">Քանակ</label>
                <div class="col-md-10">
                    <input class="form-control" placeholder="Քանակ" value="{{ $data->quantity ?? old('quantity') }}"
                        id="quantity" name="quantity" />
                </div>
                @error("quantity")
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
