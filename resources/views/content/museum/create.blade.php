@extends('layouts/contentNavbarLayout')

@section('title', 'Թանգարանի - Ստեղծում')
@section('page-script')
    <script src="{{ asset('assets/js/admin\museum\museum-upload-photo.js') }}"></script>
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/museum/museum.css') }}">
@endsection

@section('content')
    @include('includes.alert')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Թանգարան /</span> Ստեղծել նոր թանգարան
    </h4>
    <div class="card">

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-header">Ստեղծել նոր թանգարան</h5>
            </div>
        </div>
        <div class="card-body">

            <form action="{{ route('museum.add') }}" method="post" enctype="multipart/form-data">

                @foreach (languages() as $lang)
                    <div class="mb-3 row">
                        <label for="name-{{ $lang }}" class="col-md-2 col-form-label">Անվանում
                            ({{ $lang }})<span class="required-field">*</span>
                        </label>

                        <div class="col-md-10">
                            <input class="form-control" placeholder="Անվանում {{ languagesName($lang) }}ով"
                                value="{{ old("name.$lang") }}" id="name-{{ $lang }}"
                                name="name[{{ $lang }}]" />
                        </div>
                    </div>
                    @error("name.$lang")
                        <div class="mb-3 row justify-content-end">
                            <div class="col-sm-10 text-danger fts-14">{{ $message }}
                            </div>
                        </div>
                    @enderror
                @endforeach

                @foreach (languages() as $lang)
                    <div class="mb-3 row">
                        <label for="description-{{ $lang }}" class="col-md-2 col-form-label">Նկարագրություն
                            ({{ $lang }})<span class="required-field">*</span>
                        </label>

                        <div class="col-md-10">
                            <textarea class="form-control" id="description-{{ $lang }}" rows="3" name="description[{{ $lang }}]" > {{ old("description.$lang") }}</textarea>
                        </div>
                    </div>
                    @error("description.$lang")
                        <div class="mb-3 row justify-content-end">
                            <div class="col-sm-10 text-danger fts-14">{{ $message }}
                            </div>
                        </div>
                    @enderror
                @endforeach

                @foreach (languages() as $lang)
                    <div class="mb-3 row">
                        <label for="address-{{ $lang }}" class="col-md-2 col-form-label">Հասցե
                            ({{ $lang }})<span class="required-field">*</span>
                        </label>

                        <div class="col-md-10">
                            <input class="form-control" placeholder="Հասցեն {{ languagesName($lang) }}ով"
                                value="{{ old("address.$lang") }}" id="address-{{ $lang }}"
                                name="address[{{ $lang }}]" />
                        </div>
                    </div>
                    @error("address.$lang")
                        <div class="mb-3 row justify-content-end">
                            <div class="col-sm-10 text-danger fts-14">{{ $message }}
                            </div>
                        </div>
                    @enderror
                @endforeach

                @foreach (languages() as $lang)
                    <div class="mb-3 row">
                        <label for="work_days-{{ $lang }}" class="col-md-2 col-form-label">Աշխատանքային օրեր
                            ({{ $lang }})<span class="required-field">*</span>
                        </label>

                        <div class="col-md-10">
                            <input class="form-control" placeholder="Աշխատանքային օրերը {{ languagesName($lang) }}ով"
                                value="{{ trans('museum.day-hours', [], $lang) }}" id="work_days-{{ $lang }}"
                                name="work_days[{{ $lang }}]" />
                        </div>
                    </div>
                    @error("work_days.$lang")
                        <div class="mb-3 row justify-content-end">
                            <div class="col-sm-10 text-danger fts-14">{{ $message }}
                            </div>
                        </div>
                    @enderror
                @endforeach

                @foreach (languages() as $lang)
                    <div class="mb-3 row">
                        <label for="owner-{{ $lang }}" class="col-md-2 col-form-label">Տնօրենի անուն
                            ազգանուն({{ $lang }})<span class="required-field">*</span>
                        </label>

                        <div class="col-md-10">
                            <input class="form-control" placeholder="Տնօրենի անուն ազգանուն {{ languagesName($lang) }}ով"
                                value="{{ old("owner.$lang") }}" id="owner-{{ $lang }}"
                                name="owner[{{ $lang }}]" />
                        </div>
                    </div>
                    @error("owner.$lang")
                        <div class="mb-3 row justify-content-end">
                            <div class="col-sm-10 text-danger fts-14">{{ $message }}
                            </div>
                        </div>
                    @enderror
                @endforeach

                @foreach (museumPhoneCount() as $idx => $phone)
                    <div class="mb-3 row">
                        <label for="phones-{{ $phone }}" class="col-md-2 col-form-label">Թանգարանի հեռախոսահամար {{$idx+1}}
                            @if($idx == 0)
                                <span class="required-field">*</span>
                            @endif
                        </label>

                        <div class="col-md-10">
                            <input class="form-control" placeholder="Թանգարանի հեռախոսահամար {{$idx+1}}"
                                value="{{ old("phones.$phone") }}" id="phones-{{ $phone }}"
                                name="phones[{{ $phone }}]" />
                        </div>
                    </div>
                    @error("phones.$phone")
                        <div class="mb-3 row justify-content-end">
                            <div class="col-sm-10 text-danger fts-14">{{ $message }}
                            </div>
                        </div>
                    @enderror
                @endforeach
                
                <div class="mb-3 row">
                    <label for="region" class="col-md-2 col-form-label">Մարզ <span class="required-field">*</span></label>
                    <div class="col-md-10">
                        <select id="defaultSelect" name="region" class="form-select">
                            <option value="">Ընտրեք մարզը</option>
                            @foreach ($regions as $region)
                                <option value="{{ $region->name }}">{{ __('regions.' . $region->name) }}</option>
                            @endforeach
                        </select>
                        @error('region')
                            <div class="justify-content-end">
                                <div class="col-sm-10 text-danger fts-14">{{ $message }}
                                </div>
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="account_number" class="col-md-2 col-form-label">Հաշվեհամար <span
                            class="required-field">*</span></label>
                    <div class="col-md-10">
                        <input class="form-control" placeholder="Հաշվեհամար" value="{{ old('account_number') }}"
                            id="account_number" name="account_number" />
                    </div>
                </div>
                @error('account_number')
                    <div class="mb-3 row justify-content-end">
                        <div class="col-sm-10 text-danger fts-14">{{ $message }}
                        </div>
                    </div>
                @enderror

                <div class="mb-3 row">
                    <label for="email" class="col-md-2 col-form-label">Էլեկտրոնային հասցե</label>
                    <div class="col-md-10">
                        <input class="form-control" placeholder="Էլեկտրոնային հասցե" value="{{ old('email') }}"
                            id="email" name="email" />
                    </div>
                </div>
                @error('email')
                    <div class="mb-3 row justify-content-end">
                        <div class="col-sm-10 text-danger fts-14">{{ $message }}
                        </div>
                    </div>
                @enderror

                @foreach (getLinkType() as $link)
                    <div class="mb-3 row">
                        <label for="link-{{ $link }}" class="col-md-2 col-form-label">{{ getLinkNames($link) }}
                        </label>

                        <div class="col-md-10">
                            <input class="form-control" placeholder="{{ getLinkNames($link) }}-ի հղումը"
                                value="{{ old("link.$link") }}" id="link-{{ $link }}"
                                name="link[{{ $link }}]" />
                        </div>
                    </div>
                    @error("link.$link")
                        <div class="mb-3 row justify-content-end">
                            <div class="col-sm-10 text-danger fts-14">{{ $message }}
                            </div>
                        </div>
                    @enderror
                @endforeach

                <div class="mb-3 row">
                    <label for="photos" class="col-md-2 col-form-label d-flex">
                            Գլխավոր նկար<span
                            class="required-field">*</span>
                        <div class="mx-2" title="Նկարի լայնքը պետք է լինի 1520 մինչև 1550 և բարձրությունը 445 մինչև 500">
                            <svg xmlns="http://www.w3.org/2000/svg"  width="16" height="16" viewBox="0 0 512 512"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM216 336h24V272H216c-13.3 0-24-10.7-24-24s10.7-24 24-24h48c13.3 0 24 10.7 24 24v88h8c13.3 0 24 10.7 24 24s-10.7 24-24 24H216c-13.3 0-24-10.7-24-24s10.7-24 24-24zm40-208a32 32 0 1 1 0 64 32 32 0 1 1 0-64z"/></svg>
                        </div>
                    </label>
                        
                    <div class="col-md-10">
                        <div class="d-flex flex-wrap align-items-start align-items-sm-center">
                            <label for="general_photo" class="btn btn-primary me-2 mb-4" tabindex="0">
                                <span class="d-none d-sm-block">Ավելացնել գլխավոր նկար</span>
                                <i class="bx bx-upload d-block d-sm-none"></i>
                                <input type="file" id="general_photo" name="general_photo" class="account-file-input-general"
                                    hidden accept="image/png, image/jpeg" />
                            </label>
                            <div class="uploaded-images-container uploaded-photo-project" id="uploadedImagesContainerGeneral"></div>
                        </div>
                    </div>
                </div>
                @error('general_photo')
                    <div class="mb-3 row justify-content-end">
                        <div class="col-sm-10 text-danger fts-14" id="photos_div">{{ $message }}
                        </div>
                    </div>
                @enderror

                <div class="mb-3 row">
                    <label for="photos" class="col-md-2 col-form-label d-flex">Նկար
                        <div class="mx-2" title="Նկարների լայնքը պետք է լինի 446 մինչև 460 և բարձրությունը 370 մինչև 380">
                            <svg xmlns="http://www.w3.org/2000/svg"  width="16" height="16" viewBox="0 0 512 512"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM216 336h24V272H216c-13.3 0-24-10.7-24-24s10.7-24 24-24h48c13.3 0 24 10.7 24 24v88h8c13.3 0 24 10.7 24 24s-10.7 24-24 24H216c-13.3 0-24-10.7-24-24s10.7-24 24-24zm40-208a32 32 0 1 1 0 64 32 32 0 1 1 0-64z"/></svg>
                        </div>
                    </label>
                    <div class="col-md-10">
                        <div class="d-flex flex-wrap align-items-start align-items-sm-center">
                            <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                <span class="d-none d-sm-block">Ավելացնել նոր նկար</span>
                                <i class="bx bx-upload d-block d-sm-none"></i>
                                <input type="file" id="upload" name="photos[]" class="account-file-input" multiple
                                    hidden accept="image/png, image/jpeg" />
                            </label>
                            <div class="uploaded-images-container uploaded-photo-project" id="uploadedImagesContainer">
                            </div>
                        </div>
                    </div>
                </div>
                @error('photos.*')
                    <div class="mb-3 row justify-content-end">
                        <div class="col-sm-10 text-danger fts-14" id="photos_div">{{ $message }}
                        </div>
                    </div>
                @enderror

                <div class="mt-5 row justify-content-end">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">Պահպանել</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    </div>


@endsection
