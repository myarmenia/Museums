@extends('layouts/contentNavbarLayout')

@section('title', 'Tables - Basic Tables')

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Новости /</span> Создать категорию
</h4>

<hr class="my-5">

<div class="card">
  @include('includes.alert')
  <div class="d-flex justify-content-between align-items-center" >
    <div>
      <h5 class="card-header">Имя категории</h5>
    </div>
    <div>
      <a href="{{route('news-category-create-page')}}" class="btn btn-primary mx-4">Создать </a>
    </div>
  </div>
  <div class="card-body">

    <div class="table-responsive text-nowrap">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Имя</th>
            <th>Am</th>
            <th>Ru</th>
            <th>En</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @if (count($category)>0)
            @foreach ($category as $item )
            <tr>
              <td><span class="fw-medium">{{$item['type']}}</span></td>
              <td><span class="fw-medium">{{$item['translations']['am']->name}}</span></td>
              <td><span class="fw-medium">{{$item['translations']['ru']->name}}</span></td>
              <td><span class="fw-medium">{{$item['translations']['en']->name}}</span></td>
              <td>
                <div class="dropdown">
                  <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                    <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</a>
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
