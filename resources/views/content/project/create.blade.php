@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')
@section('page-script')
    <script src="{{ asset('assets/js/admin\project\project-upload-photo.js') }}"></script>
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{asset('assets/css/admin/project/project.css')}}">
@endsection

@section('content')

    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Проекты /</span> Создать нового проект
    </h4>
    <div class="card">

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-header">Создать нового проект</h5>
            </div>
        </div>
        <div class="card-body">

            <form action="{{ route('project.add') }}" method="post" enctype="multipart/form-data">

                <div class="mb-3 row">
                    <label for="name" class="col-md-2 col-form-label">Имя проекта</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" placeholder="Имя" id="name" name="name"
                            value="{{ old('name') }}">
                    </div>
                </div>
                @error('name')
                    <div class="mb-3 row justify-content-end">
                        <div class="col-sm-10 text-danger fts-14">{{ $message }}
                        </div>
                    </div>
                @enderror

                <div class="mb-3 row">
                    <label for="proj-am" class="col-md-2 col-form-label">Описание Арм.</label>
                    <div class="col-md-10">
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="Описание Арм."
                            id="proj-am" name="proj-am" value="{{ old('proj-am') }}"></textarea>
                    </div>
                </div>
                @error('proj-am')
                    <div class="mb-3 row justify-content-end">
                        <div class="col-sm-10 text-danger fts-14">{{ $message }}
                        </div>
                    </div>
                @enderror

                <div class="mb-3 row">
                    <label for="proj-ru" class="col-md-2 col-form-label">Описание Ру.</label>
                    <div class="col-md-10">
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="Описание Ру." id="proj-ru"
                            name="proj-ru" value="{{ old('proj-ru') }}"></textarea>
                    </div>
                </div>
                @error('proj-ru')
                    <div class="mb-3 row justify-content-end">
                        <div class="col-sm-10 text-danger fts-14">{{ $message }}
                        </div>
                    </div>
                @enderror

                <div class="mb-3 row">
                    <label for="proj-en" class="col-md-2 col-form-label">Описание Анг.</label>
                    <div class="col-md-10">
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="Описание Анг."
                            id="proj-en" name="proj-en" value="{{ old('proj-en') }}"></textarea>
                    </div>
                </div>
                @error('proj-en')
                    <div class="mb-3 row justify-content-end">
                        <div class="col-sm-10 text-danger fts-14">{{ $message }}
                        </div>
                    </div>
                @enderror

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
                        <input class="form-control" type="number" placeholder="Начало процесса (год)" id="creation_date_at"
                            name="creation_date_at" value="{{ old('creation_date_at') }}">
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
                        <div class="col-sm-10 text-danger fts-14" >{{ $message }}
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
                                <input type="file" id="upload" name="project_photos[]" class="account-file-input" multiple hidden
                                    accept="image/png, image/jpeg" />
                            </label>
                            <div class="uploaded-images-container uploaded-photo-project" id="uploadedImagesContainer"></div>

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
