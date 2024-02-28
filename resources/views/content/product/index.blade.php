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
                  <a href="{{route('product-list')}}">Ապրանքներ </a>
              </li>
              <li class="breadcrumb-item active">Ցանկ</li>
          </ol>
      </nav>
  </h4>
    <div class="card">

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-header">Ապրանքների ցանկ</h5>
            </div>
            <div>
                <a href="{{ route('product-create') }}" class="btn btn-primary mx-4">Ստեղծել Ապրանք </a>
            </div>
        </div>
        <div class="card-body">
          <form action="{{route('product-list')}}" method="get" class="row g-3 mt-2" style="display: flex">
            <div class="mb-3 justify-content-end" style="display: flex; gap: 8px">
              <div class="col-2">
                <input type="text" class="form-control" id="" placeholder="Անվանում" name="name" value="{{ request()->input('name') }}">
            </div>
            <div class="mb-3 row">

              <div class="col-md-10">
                  <select id="defaultSelect" name="product_category_id" class="form-select" value="{{ request()->input('product_category_id') }}" >
                      <option value="">ֆիլտրել ըստ կատեգորիաի</option>
                      @foreach ($data as $dat)
                          <option value="{{ $dat->id }}">{{ __('product-categories.' . $dat->key) }}</option>
                      @endforeach
                  </select>
              </div>
            </div>



                <button class="btn btn-primary col-2">Փնտրել</button>

            </div>
          </form>

            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Նկար</th>
                            <th>Անվանում</th>
                            <th>Կարգավիճակ</th>
                            <th>Ստեղծման ամսաթիվը</th>
                            <th>Գործողություն</th>
                        </tr>
                    </thead>
                    <tbody>
@php
  $i=0;
@endphp

@if($data!=false)
                        @foreach ($data as $key => $item)

                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>
                                    @if($item['images'])
                                        <img width="50" height="50" src="{{route('get-file',['path'=>$item->images[0]->path]) }}" >
                                    @endif
                                </td>
                                <td>{{ $item->translation("am")->name}}</td>
                                <td class="status">
                                  @if ($item->status)
                                      <span class="badge bg-label-success me-1">Ակտիվ</span>
                                  @else
                                      <span class="badge bg-label-danger me-1">Ապաակտիվ</span>
                                  @endif
                              </td>


                                <td>{{ $item->created_at }}</td>
                                <td>
                                    <div class="dropdown action" data-id="{{ $item['id'] }}" data-tb-name="products">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                          <a class="dropdown-item d-flex" href="javascript:void(0);">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input change_status" type="checkbox"
                                                    role="switch" data-field-name="status"
                                                    {{ $item['status'] ? 'checked' : null }}>
                                            </div>Կարգավիճակ
                                        </a>
                                            <a class="dropdown-item" href="{{route('product-edit',$item['id'])}}"><i
                                                    class="bx bx-edit-alt me-1"></i>Խմբագրել</a>
                                            <button type="button" class="dropdown-item click_delete_item"
                                                data-bs-toggle="modal" data-bs-target="#smallModal"><i
                                                    class="bx bx-trash me-1"></i>
                                                Ջնջել</button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
@endif
                    </tbody>
                </table>
            </div>
            {{-- {{$data->links() }} --}}

        </div>

    </div>



@endsection
<x-modal-delete></x-modal-delete>
