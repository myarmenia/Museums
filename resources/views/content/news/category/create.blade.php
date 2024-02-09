@extends('layouts/contentNavbarLayout')

@section('title', ' Horizontal Layouts - Forms')
@section('page-script')
    <script src="{{ asset('assets/js/delete-file.js') }}"></script>
@endsection

@section('content')
    <h4 class="py-3 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);">Категорию новости</a>
                </li>
                <li class="breadcrumb-item active">Список</li>
            </ol>
        </nav>
    </h4>

    <div class="row">
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-header"> Добавить категорию новости</h5>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('news-category-create') }}" method="post" enctype="multipart/form-data">
                        <div class="mb-3 row">
                            <label for="type" class="col-md-2 col-form-label">Названия</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" placeholder="Название типа должно состоять из латинских букв и без пробелов." id="type"
                                    name="type" value="{{ old('type') }}">
                            </div>
                        </div>
                        @error('type')
                            <div class="mb-3 row justify-content-end">
                                <div class="col-sm-10 text-danger fts-14">{{ $message }}
                                </div>
                            </div>
                        @enderror
                        @foreach (languages() as $lang)
                            <div class="mb-3 row">
                                <label for="description-{{ $lang }}" class="col-md-2 col-form-label">Названия
                                    {{ $lang }}</label>

                                <div class="col-md-10">
                                    <input class="form-control" placeholder="Названия"
                                        value="{{ old("translate.$lang.description") }}"
                                        id="description-{{ $lang }}"
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

                        <div class="row justify-content-end mt-3">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">Добавить</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
