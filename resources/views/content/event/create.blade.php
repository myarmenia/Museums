@extends('layouts/contentNavbarLayout')

@section('page-script')

    <script src="{{ asset('assets/js/admin\news\index.js') }}"></script>
    <script src="{{ asset('assets/js/admin/event/change_event_style.js') }}"></script>
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/project/project.css') }}">
@endsection

@section('content')

    <h4 class="py-3 mb-4">
      <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
              <li class="breadcrumb-item">
                  <a href="{{route('event_list')}}">Միջոցառում </a>
              </li>
              <li class="breadcrumb-item active">Ստեղծել միջոցառում</li>
          </ol>
      </nav>
  </h4>
    <div class="card">

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-header">Ստեղծել միջոցառում </h5>
            </div>

        </div>
        <div class="card-body">


            <form action="{{ route('event_store') }}" method="post" enctype="multipart/form-data">
              <div class="mb-3 row">
                <label for="region" class="col-md-2 col-form-label"> Տեսակ <span class="required-field">*</span></label>
                <div class="col-md-10">
                    <select id="defaultSelect" name="style" class="form-select"  onchange="changeEventType()">
                        <option value="" disabled>Ընտրեք տեսակ</option>
                        <option value="basic" {{ old('style') == 'basic' ? 'selected' : '' }}>Միջոցառում</option>
                        <option value="temporary" {{ old('style') == 'temporary' ? 'selected' : '' }}>Ժամանակավոր ցուցադրություն</option>
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
                              value="{{ old("translate.$lang.name") }}"
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
              @foreach (languages() as $lang)
                <div class="mb-3 row">
                  <label for="description-{{ $lang }}" class="col-md-2 col-form-label">Նկարագիր
                      {{ $lang }}
                      <span class="required-field text-danger">*</span>
                  </label>


                  <div class="col-md-10">
                      <textarea id="description-{{ $lang }}" class="form-control" placeholder="Նկարագիր"
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
                <label for="phone_number" class="col-md-2 col-form-label">Միջոցառման սկիզբ
                  <span class="required-field text-danger">*</span>
                </label>
                <div class="col-md-10">
                    <input class="form-control" type="date" placeholder="Միջոցառման սկիզբ" value="{{ old('start_date') }}"
                        id="start_date" name="start_date" />
                </div>
                @error("start_date")
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
                    <input class="form-control" type="date" placeholder="" value="{{ old('end_date') }}"
                        id="end_date" name="end_date" />
                </div>
                @error("end_date")
                  <div class="mb-3 row justify-content-end">
                      <div class="col-sm-10 text-danger fts-14">{{ $message }}
                      </div>
                  </div>
                @enderror
              </div>
              <div class="mb-3 row" id="ticket_max_quantity" style="display:{{old('style') == 'temporary' ? 'none' : 'flex' }}">
                <label for="phone_number" class="col-md-2 col-form-label">Տոմսերի առավելագույն քանակ մեկ օրվա համար
                  <span class="required-field text-danger">*</span>
                </label>
                <div class="col-md-10">
                    <input class="form-control" placeholder="Տոմսերի առավելագույն քանակ մեկ օրվա համար" value="{{ old('visitors_quantity_limitation') }}"
                        id="visitors_quantity_limitation" name="visitors_quantity_limitation" />
                </div>
                @error("visitors_quantity_limitation")
                  <div class="mb-3 row justify-content-end">
                      <div class="col-sm-10 text-danger fts-14">{{ $message }}
                      </div>
                  </div>
                @enderror
              </div>
              <div class="mb-3 row" >
                <label for="email" class="col-md-2 col-form-label">Ստանդարտ Գին
                  <span class="required-field text-danger">*</span>
                </label>

                <div class="col-md-10">
                    <input class="form-control" placeholder="Ստանդարտ Գին" value="{{ old('price') }}"
                        id="price" name="price" />
                </div>
                @error("price")
                  <div class="mb-3 row justify-content-end">
                      <div class="col-sm-10 text-danger fts-14">{{ $message }}
                      </div>
                  </div>
                @enderror
              </div>
              <div class="mb-3 row" >
                <label for="email" class="col-md-2 col-form-label">Զեղչված Գին

                </label>

                <div class="col-md-10">
                    <input class="form-control" placeholder="Զեղչված Գին" value="{{ old('discount_price') }}"
                        id="discount_price" name="discount_price" />
                </div>
                @error("discount_price")
                  <div class="mb-3 row justify-content-end">
                      <div class="col-sm-10 text-danger fts-14">{{ $message }}
                      </div>
                  </div>
                @enderror
              </div>
              <div class="mb-3 row" >
                <label for="email" class="col-md-2 col-form-label">Էքսկուրսավար(հայերեն)

                </label>

                <div class="col-md-10">
                    <input class="form-control" placeholder="Հայալեզու էքսկուրսիաի գինը" value="{{ old('guide_price_am') }}"
                        id="guide_price_am" name="guide_price_am" />
                </div>
                @error("guide_price_am")
                  <div class="mb-3 row justify-content-end">
                      <div class="col-sm-10 text-danger fts-14">{{ $message }}
                      </div>
                  </div>
                @enderror
              </div>
              <div class="mb-3 row" >
                <label for="email" class="col-md-2 col-form-label">Էքսկուրսավար(այլ)

                </label>

                <div class="col-md-10">
                    <input class="form-control" placeholder="Այլ լեզվով էքսկուրսիաի գինը" value="{{ old('guide_price_other') }}"
                        id="guide_price_other" name="guide_price_other" />
                </div>
                @error("guide_price_other")
                  <div class="mb-3 row justify-content-end">
                      <div class="col-sm-10 text-danger fts-14">{{ $message }}
                      </div>
                  </div>
                @enderror
              </div>




                <div class="mb-3 row">
                    <label for="photo" class="col-md-2 col-form-label">Միջոցառման նկար
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
                      <button type="submit" class="btn btn-primary">Ստեղծել</button>
                  </div>
              </div>
              </div>


            </form>

        </div>

    </div>

@endsection
