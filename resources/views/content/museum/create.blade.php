@extends('layouts/contentNavbarLayout')

@section('title', 'Թանգարանի - Ստեղծում')
{{-- @section('page-script')
    <script src="{{ asset('assets/js/admin\project\project-upload-photo.js') }}"></script>
@endsection --}}

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/project/project.css') }}">
@endsection

@section('content')

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
                        <label for="name-{{ $lang }}" class="col-md-2 col-form-label">Անվանում*
                        </label>

                        <div class="col-md-10">
                            <input class="form-control" placeholder="Անվանում {{ languagesName($lang) }}ով"
                                value="{{ old("translate.$lang.name") }}" id="name-{{ $lang }}"
                                name="translate[{{ $lang }}]" />
                        </div>
                    </div>
                    @error("translate.$lang")
                        <div class="mb-3 row justify-content-end">
                            <div class="col-sm-10 text-danger fts-14">{{ $message }}
                            </div>
                        </div>
                    @enderror
                @endforeach

                @foreach (languages() as $lang)
                    <div class="mb-3 row">
                        <label for="description-{{ $lang }}" class="col-md-2 col-form-label">Նկարագրություն*
                        </label>

                        <div class="col-md-10">
                            {{-- <input class="form-control" placeholder="Նկարագրություն {{ languagesName($lang) }}ով"
                                value="{{ old("translate.$lang.description") }}" id="description-{{ $lang }}"
                                name="translate[{{ $lang }}]" /> --}}
                            <textarea class="form-control" id="description-{{ $lang }}" rows="3"
                                placeholder="Նկարագրություն {{ languagesName($lang) }}ով" name="translate[{{ $lang }}]">
                            </textarea>
                        </div>
                    </div>
                    @error("translate.$lang")
                        <div class="mb-3 row justify-content-end">
                            <div class="col-sm-10 text-danger fts-14">{{ $message }}
                            </div>
                        </div>
                    @enderror
                @endforeach

                <div class="mb-3 row">
                    <label for="project_photos" class="col-md-2 col-form-label">Գլխավոր նկար</label>
                    <div class="col-md-10">
                        <div class="d-flex flex-wrap align-items-start align-items-sm-center">
                            <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                <span class="d-none d-sm-block">Upload new photos</span>
                                <i class="bx bx-upload d-block d-sm-none"></i>
                                <input type="file" id="upload" name="project_photos[]" class="account-file-input"
                                    multiple hidden accept="image/png, image/jpeg" />
                            </label>
                            <div class="uploaded-images-container uploaded-photo-project" id="uploadedImagesContainer">
                            </div>

                        </div>
                    </div>
                </div>
                @error('project_photos')
                    <div class="mb-3 row justify-content-end">
                        <div class="col-sm-10 text-danger fts-14" id="photos_div">{{ $message }}
                        </div>
                    </div>
                @enderror

                <div class="mb-3 row">
                    <label for="project_photos" class="col-md-2 col-form-label">Գլխավոր նկար</label>
                    <div class="col-md-10">
                        <div class="d-flex flex-wrap align-items-start align-items-sm-center">
                            <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                <span class="d-none d-sm-block">Upload new photos</span>
                                <i class="bx bx-upload d-block d-sm-none"></i>
                                <input type="file" id="upload" name="project_photos[]" class="account-file-input"
                                    multiple hidden accept="image/png, image/jpeg" />
                            </label>
                            <div class="uploaded-images-container uploaded-photo-project" id="uploadedImagesContainer">
                            </div>

                        </div>
                    </div>
                </div>
                @error('project_photos')
                    <div class="mb-3 row justify-content-end">
                        <div class="col-sm-10 text-danger fts-14" id="photos_div">{{ $message }}
                        </div>
                    </div>
                @enderror

                @foreach (languages() as $lang)
                    <div class="mb-3 row">
                        <label for="address-{{ $lang }}" class="col-md-2 col-form-label">Հասցե*
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
                        <label for="work_days-{{ $lang }}" class="col-md-2 col-form-label">Աշխատանքային օրեր*
                        </label>

                        <div class="col-md-10">
                            <input class="form-control" placeholder="Աշխատանքային օրերը {{ languagesName($lang) }}ով"
                                value="{{ old("work_days.$lang") }}" id="work_days-{{ $lang }}"
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

                <div class="mb-3 row">
                    <label for="work_hours" class="col-md-2 col-form-label">Աշխատանքային ժամեր*</label>
                    <div class="col-md-10">
                        <input class="form-control" placeholder="Աշխատանքային ժամերը" value="{{ old('work_hours') }}"
                            id="work_hours" name="work_hours" />
                    </div>
                </div>
                @error('work_hours')
                    <div class="mb-3 row justify-content-end">
                        <div class="col-sm-10 text-danger fts-14">{{ $message }}
                        </div>
                    </div>
                @enderror

                @foreach (languages() as $lang)
                    <div class="mb-3 row">
                        <label for="work_days-{{ $lang }}" class="col-md-2 col-form-label">Աշխատանքային օրեր*
                        </label>

                        <div class="col-md-10">
                            <input class="form-control" placeholder="Աշխատանքային օրերը {{ languagesName($lang) }}ով"
                                value="{{ old("work_days.$lang") }}" id="work_days-{{ $lang }}"
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
                        <label for="owner-{{ $lang }}" class="col-md-2 col-form-label">Տնօրենի անուն ազգանուն*
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

                <div class="mb-3 row">
                    <label for="phone" class="col-md-2 col-form-label">Թանգարանի հեռախոսահամար*</label>
                    <div class="col-md-10">
                        <input class="form-control" placeholder="Թանգարանի հեռախոսահամար" value="{{ old('phone') }}"
                            id="phone" name="phone" />
                    </div>
                </div>
                @error('phone')
                    <div class="mb-3 row justify-content-end">
                        <div class="col-sm-10 text-danger fts-14">{{ $message }}
                        </div>
                    </div>
                @enderror

                @foreach (languages() as $lang)
                    <div class="mb-3 row">
                        <label for="branch-{{ $lang }}" class="col-md-2 col-form-label">Թանգարանի մասնաճյուղ
                        </label>

                        <div class="col-md-10">
                            <input class="form-control" placeholder="Թանգարանի մասնաճյուղ {{ languagesName($lang) }}ով"
                                value="{{ old("branch.$lang") }}" id="branch-{{ $lang }}"
                                name="branch[{{ $lang }}]" />
                        </div>
                    </div>
                    @error("branch.$lang")
                        <div class="mb-3 row justify-content-end">
                            <div class="col-sm-10 text-danger fts-14">{{ $message }}
                            </div>
                        </div>
                    @enderror
                @endforeach

                <div class="mb-3 row">
                    <label for="region" class="col-md-2 col-form-label">Մարզ</label>
                    <div class="col-md-10">
                        <select id="defaultSelect" name="type" class="form-select">
                            <option>Выберите тип проекта</option>
                            <option value="web">Web</option>
                            <option value="mobile">Mobile</option>
                            <option value="3d">3d</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="lang" class="col-md-2 col-form-label">Языки програм.</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" placeholder="Языки программирования" id="lang"
                            name="lang" value="{{ old('lang') }}">

                    </div>
                </div>
                @error('lang')
                    <div class="mb-3 row justify-content-end">
                        <div class="col-sm-10 text-danger fts-14">{{ $message }}
                        </div>
                    </div>
                @enderror

                <div class="mb-3 row">
                    <label for="process_time" class="col-md-2 col-form-label">Время процесса</label>
                    <div class="col-md-10">
                        <input class="form-control" type="number"
                            placeholder="Время процесса изготовления программы (месяц)" id="process_time"
                            name="process_time" value="{{ old('process_time') }}">
                    </div>
                </div>
                @error('process_time')
                    <div class="mb-3 row justify-content-end">
                        <div class="col-sm-10 text-danger fts-14">{{ $message }}
                        </div>
                    </div>
                @enderror

                <div class="mb-3 row">
                    <label for="creation_date_at" class="col-md-2 col-form-label">Начало процесса</label>
                    <div class="col-md-10">
                        <input class="form-control" type="number" placeholder="Начало процесса (год)"
                            id="creation_date_at" name="creation_date_at" value="{{ old('creation_date_at') }}">
                    </div>
                </div>
                @error('creation_date_at')
                    <div class="mb-3 row justify-content-end">
                        <div class="col-sm-10 text-danger fts-14">{{ $message }}
                        </div>
                    </div>
                @enderror

                <div class="mb-3 row">
                    <label for="link_project" class="col-md-2 col-form-label">Ссилка проекта</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" placeholder="Ссилка проекта" id="link_project"
                            name="link_project" value="{{ old('link_project') }}">
                    </div>
                </div>
                @error('link_project')
                    <div class="mb-3 row justify-content-end">
                        <div class="col-sm-10 text-danger fts-14">{{ $message }}
                        </div>
                    </div>
                @enderror

                <div class="mb-3 row">
                    <label for="link_app_store" class="col-md-2 col-form-label">Ссилка проекта в App Store</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" placeholder="Ссилка проекта в App Store"
                            id="link_app_store" name="link_app_store" value="{{ old('link_app_store') }}">
                    </div>
                </div>
                @error('link_app_store')
                    <div class="mb-3 row justify-content-end">
                        <div class="col-sm-10 text-danger fts-14">{{ $message }}
                        </div>
                    </div>
                @enderror

                <div class="mb-3 row">
                    <label for="link_play_market" class="col-md-2 col-form-label">Ссилка проекта в Play Market</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" placeholder="Ссилка проекта в Play Market"
                            id="link_play_market" name="link_play_market" value="{{ old('link_play_market') }}">
                    </div>
                </div>
                @error('link_play_market')
                    <div class="mb-3 row justify-content-end">
                        <div class="col-sm-10 text-danger fts-14">{{ $message }}
                        </div>
                    </div>
                @enderror

                <div class="mb-3 row">
                    <label for="link_play_market" class="col-md-2 col-form-label">Тип проекта</label>
                    <div class="col-md-10">
                        <select id="defaultSelect" name="type" class="form-select">
                            <option>Выберите тип проекта</option>
                            <option value="web">Web</option>
                            <option value="mobile">Mobile</option>
                            <option value="3d">3d</option>
                        </select>
                    </div>
                </div>
                @error('type')
                    <div class="mb-3 row justify-content-end">
                        <div class="col-sm-10 text-danger fts-14">{{ $message }}
                        </div>
                    </div>
                @enderror


                <div class="mb-3 row">
                    <label for="project_photos" class="col-md-2 col-form-label">Проект фото</label>
                    <div class="col-md-10">
                        <div class="d-flex flex-wrap align-items-start align-items-sm-center">
                            <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                <span class="d-none d-sm-block">Upload new photos</span>
                                <i class="bx bx-upload d-block d-sm-none"></i>
                                <input type="file" id="upload" name="project_photos[]" class="account-file-input"
                                    multiple hidden accept="image/png, image/jpeg" />
                            </label>
                            <div class="uploaded-images-container uploaded-photo-project" id="uploadedImagesContainer">
                            </div>

                        </div>
                    </div>
                </div>
                @error('project_photos')
                    <div class="mb-3 row justify-content-end">
                        <div class="col-sm-10 text-danger fts-14" id="photos_div">{{ $message }}
                        </div>
                    </div>
                @enderror

                <div class="mt-5 row justify-content-end">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </div>
        </div>

        </form>
    </div>


    </div>


@endsection
