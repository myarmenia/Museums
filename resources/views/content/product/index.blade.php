@extends('layouts/contentNavbarLayout')


@section('page-script')
<script src="{{ asset('assets/js/change-status.js') }}"></script>
    <script src="{{ asset('assets/js/delete-item.js') }}"></script>
@endsection

@section('content')
    {{-- @include('includes.alert') --}}
    {{-- {{ dd(!museumAccessId()) }} --}}
    @if (auth()->user()->role=='museum_admin'|| auth()->user()->role== 'content_manager')
        @if (museumAccessId()==null)
        <div class="alert alert-danger">
          Նախ ստեղծեք թանգարան
        </div>

    @endif

    @endif





    <h4 class="py-3 mb-4">
      <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
              <li class="breadcrumb-item">
                  <a href="{{route('product_list')}}">Ապրանքներ </a>
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
            @if (museumAccessId()!=null)
              <div>
                  <a href="{{ route('product_create') }}" class="btn btn-primary mx-4">Ստեղծել Ապրանք </a>
              </div>

            @endif
        </div>
        <div class="card-body">
          <form action="{{route('product_list')}}" method="get" class="row g-3 mt-2" style="display: flex">
            <div class="mb-3 justify-content-end" style="display: flex; gap: 8px">
              <div class="col-2">
                <input type="text" class="form-control" id="" placeholder="Անվանում" name="name" value="{{ request()->input('name') }}">
            </div>
            <div class="mb-3 row">

              <div class="col-md-10">
                  <select id="defaultSelect" name="product_category_id" class="form-select" value="{{ request()->input('product_category_id') }}" >
                      <option value="">ֆիլտրել ըստ կատեգորիաի</option>
                      @foreach ($product_category as $item)
                          <option value="{{ $item->id }}">{{ __('product-categories.' . $item->key) }}</option>
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
                            @if (museumAccessId()!=null)
                              <th>Գործողություն</th>
                            @endif

                        </tr>
                    </thead>
                    <tbody>


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
                                @if (museumAccessId()!=null)
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
                                          <a class="dropdown-item" href="{{route('product_edit',$item['id'])}}"><i
                                                  class="bx bx-edit-alt me-1"></i>Խմբագրել</a>
                                          <button type="button" class="dropdown-item click_delete_item"
                                              data-bs-toggle="modal" data-bs-target="#smallModal"><i
                                                  class="bx bx-trash me-1"></i>
                                              Ջնջել</button>
                                      </div>
                                  </div>
                              </td>

                                @endif

                            </tr>
                        @endforeach
@endif
                    </tbody>
                </table>
            </div>
            {{$data->links() }}

        </div>

    </div>



@endsection
<x-modal-delete></x-modal-delete>