@extends('layouts/contentNavbarLayout')

@section('title', 'Tables - Basic Tables')
@section('page-script')

    <script src="{{ asset('assets/js/delete-item.js') }}"></script>
@endsection

@section('content')

<h4 class="py-3 mb-4">
  <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
          <li class="breadcrumb-item">
              <a href="{{route('lesson-list')}}">Уроки</a>
          </li>
          <li class="breadcrumb-item active">Список</li>
      </ol>
  </nav>
</h4>

<!-- Basic Bootstrap Table -->

<!-- Bordered Table -->
<div class="card">

  <div class="d-flex justify-content-between align-items-center" >
    <div>
      <h5 class="card-header">Список уроков</h5>
    </div>
    <div>
      <a href="{{route('lesson-create')}}" class="btn btn-primary mx-4">Создавать </a>
    </div>
  </div>
  <div class="card-body">

    <div class="table-responsive text-nowrap">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>No</th>
            <th>Название урока</th>
            <th>Номер урока</th>
            <th>Название языка</th>
            <th>Действия</th>
          </tr>
        </thead>
        <tbody>

          @if (count($lessons)>0)
            @foreach ($lessons as $item )
          
              <tr>
                <td>{{++$i}}</td>
                <td>
                   <span class="fw-medium">{{ $item->translation("ru")->title }}</span></td>
                <td>{{$item->number}}</td>
                <td>{{$item->course_languages->name}}</td>
                <td>
                  <div class="dropdown action" data-id="{{ $item->id }}" data-tb-name="lessons">

                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="{{route('lesson-edit',$item->id)}}"><i class="bx bx-edit-alt me-1"></i> Редактировать</a>
                      <button type="button" class="dropdown-item click_delete_item"
                      data-bs-toggle="modal" data-bs-target="#smallModal"><i
                          class="bx bx-trash me-1"></i>
                          Удалить</button>
                    </div>
                  </div>
                </td>
              </tr>
            @endforeach

          @else
          <tr>
            <td colspan="3">Нет записи</td>
          </tr>

          @endif
        </tbody>
      </table>
    </div>
    <div class="demo-inline-spacing">
      {{ $lessons->links() }}
    </div>
  </div>
</div>


@endsection
<x-modal-delete></x-modal-delete>
