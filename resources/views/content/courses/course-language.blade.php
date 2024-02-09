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
              <a href="{{route('course-language')}}">Языки программирования</a>
          </li>
          <li class="breadcrumb-item active">Список</li>
      </ol>
  </nav>
</h4>



<!-- Bordered Table -->
<div class="card ">

  <div class="d-flex justify-content-between align-items-center" >
    <div>
      <h5 class="card-header">Список языков программирования
      </h5>
    </div>
    <div>
      <a href="{{route('course-language-create')}}" class="btn btn-primary mx-4">Создавать </a>
    </div>
  </div>
  <div class="card-body ">

    <div class="table-responsive text-nowrap">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>No</th>
            <th >Названия</th>
            <th >Описание</th>
            <th>Действия</th>
          </tr>
        </thead>
        <tbody>
          @if (count($course_language)>0)
          @php
          $i=0;

          @endphp

            @foreach ($course_language as $item )

            <tr>
              <td style="width:30px">
                @php echo ++$i; @endphp
              </td>
              <td >
                <img src="{{route('get-file',['path'=>$item->logo])}}" alt="user-avatar" class="rounded" height="50" width="50" id="uploadedFile">
                {{-- <i class="bx bxl-angular bx-sm text-danger me-3"></i> --}}
                 <span class="fw-medium">{{$item->name}}</span></td>
              {{-- <td><span class="badge bg-label-primary me-1">Active</span></td> --}}
              <td>{{ $item->coursetranslation('ru')->description }}</td>
              <td>
                <div class="dropdown action" data-id="{{ $item->id }}" data-tb-name="course_languages">
                  <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('course-language-edit',$item->id) }}"><i class="bx bx-edit-alt me-1"></i> Редактировать</a>
                    {{-- <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</a> --}}
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
            <td colspan="3">There is no record </td>
          </tr>

          @endif
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
<x-modal-delete></x-modal-delete>
