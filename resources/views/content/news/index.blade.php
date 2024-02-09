@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')
@section('page-script')
    <script src="{{ asset('assets/js/change-status.js') }}"></script>
    <script src="{{ asset('assets/js/111.js') }}"></script>
    <script src="{{ asset('assets/js/delete-item.js') }}"></script>
@endsection

@section('content')
    @include('includes.alert')

    <h4 class="py-3 mb-4">
      <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
              <li class="breadcrumb-item">
                  <a href="{{route('news')}}">Новости </a>
              </li>
              <li class="breadcrumb-item active">Список</li>
          </ol>
      </nav>
  </h4>
    <div class="card">

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-header">Список новостей</h5>
            </div>
            <div>
                <a href="{{ route('news-create-page') }}" class="btn btn-primary mx-4">Создать новость </a>
            </div>
        </div>
        <div class="card-body">

            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Фото</th>
                            <th>Названия</th>
                            <th>Категория</th>
                            <th>Дата создания</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($data as $key => $news)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>
                                    @if($news['images'])
                                        <img width="50" height="50" src="{{ $news['images'] }}" >
                                    @endif
                                </td>
                                <td>{{ $news['title'] }}</td>
                                <td>{{ $news['category'] }}
                                <td>{{ $news['created_at'] }}</td>
                                <td>
                                    <div class="dropdown action" data-id="{{ $news['id'] }}" data-tb-name="news">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{route('news-edit',$news['id'])}}"><i
                                                    class="bx bx-edit-alt me-1"></i>Редактировать</a>
                                            <button type="button" class="dropdown-item click_delete_item"
                                                data-bs-toggle="modal" data-bs-target="#smallModal"><i
                                                    class="bx bx-trash me-1"></i>
                                                Удалить</button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


@endsection
<x-modal-delete></x-modal-delete>
