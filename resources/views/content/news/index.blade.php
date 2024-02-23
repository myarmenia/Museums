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
                  <a href="{{route('news')}}">Նորություններ </a>
              </li>
              <li class="breadcrumb-item active">Ցուցակ</li>
          </ol>
      </nav>
  </h4>
    <div class="card">

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-header">Նորությունների ցուցակ</h5>
            </div>
            <div>
                <a href="{{ route('news-create-page') }}" class="btn btn-primary mx-4">Ստեղծել նորություն </a>
            </div>
        </div>
        <div class="card-body">
          <form action="{{route('news')}}" method="get" class="row g-3 mt-2" style="display: flex">
            <div class="mb-3 justify-content-end" style="display: flex; gap: 8px">
              <div class="col-2">
                <input type="text" class="form-control" id="" placeholder="Վերնագիր" name="title" value="{{ request()->input('title') }}">
            </div>
                <div class="col-2">
                    <input type="date" class="form-control" id="inputEmail" placeholder="Ստեղծման ամսաթիվ" name="from_created_at" value="{{ request()->input('from_created_at') }}">
                </div>

                <div class="col-2">
                    <input type="date" class="form-control" id="inputPhone" placeholder="Ստեղծման ամսաթիվ" name="to_created_at" value="{{ request()->input('to_created_at') }}">
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
                            <th>Վերնագիր</th>
                            <th>Կարգավիճակ</th>
                            <th>Ստեղծման ամսաթիվը</th>
                            <th>Գործողություն</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($data as $key => $news)

                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>
                                    @if($news['images'])
                                        <img width="50" height="50" src="{{route('get-file',['path'=>$news->images[0]->path]) }}" >
                                    @endif
                                </td>
                                <td>{{ $news->translation("am")->title}}</td>
                                <td class="status">
                                  @if ($news->status)
                                      <span class="badge bg-label-success me-1">Ակտիվ</span>
                                  @else
                                      <span class="badge bg-label-danger me-1">Ապաակտիվ</span>
                                  @endif
                              </td>


                                <td>{{ $news->created_at }}</td>
                                <td>
                                    <div class="dropdown action" data-id="{{ $news['id'] }}" data-tb-name="news">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                          <a class="dropdown-item d-flex" href="javascript:void(0);">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input change_status" type="checkbox"
                                                    role="switch" data-field-name="status"
                                                    {{ $news['status'] ? 'checked' : null }}>
                                            </div>Կարգավիճակ
                                        </a>
                                            <a class="dropdown-item" href="{{route('news-edit',$news['id'])}}"><i
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
                    </tbody>
                </table>
            </div>
            {{ $data->links() }}

        </div>

    </div>



@endsection
<x-modal-delete></x-modal-delete>
