@extends('layouts/contentNavbarLayout')
@section('title', 'Այլ ծառայություններ - Ցանկ')

@section('page-script')
    <script src="{{ asset('assets/js/change-status.js') }}"></script>
    <script src="{{ asset('assets/js/delete-item.js') }}"></script>
@endsection

@section('content')
    @if (!museumAccessId())
        <div class="alert alert-danger"> Նախ ստեղծեք թանգարան </div>
    @endif

    <h4 class="py-3 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);">Այլ ծառայություններ</a>
                </li>
                <li class="breadcrumb-item active">Ցանկ</li>
            </ol>
        </nav>
    </h4>
    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-header">Այլ ծառայությունների ցանկ</h5>
            </div>
            @if (museumAccessId())
                <div>
                  <a href="{{ route('other_services_create') }}" class="btn btn-primary mx-4">Ստեղծել նոր ծառայություն</a>
                </div>
            @endif
        </div>
        <div class="card-body">

            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Անվանում</th>
                            <th>Գին</th>
                            <th>Կարգավիճակ</th>
                            <th>Գործողություն</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($data) > 0)
                            @foreach ($data as $key => $service)

                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $service->translation("am")->name }}</td>
                                    <td>{{ $service->price }}</td>
                                    <td class="status">
                                        @if ($service->status)
                                            <span class="badge bg-label-success me-1">Ակտիվ</span>
                                        @else
                                            <span class="badge bg-label-danger me-1">Ապաակտիվ</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown action" data-id="{{ $service->id }}" data-tb-name="other_services">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>

                                            <div class="dropdown-menu">

                                                <a class="dropdown-item d-flex" href="javascript:void(0);">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input change_status" type="checkbox"
                                                            role="switch" data-field-name="status"
                                                            {{ $service->status ? 'checked' : null }}>
                                                    </div>Կարգավիճակ
                                                </a>
                                                <a class="dropdown-item" href="{{route('other_services-edit', $service->id)}}"><i
                                                    class="bx bx-edit-alt me-1"></i>Փոփոխել
                                                </a>
                                                <button type="button" class="dropdown-item click_delete_item"
                                                    data-bs-toggle="modal" data-bs-target="#smallModal"><i
                                                        class="bx bx-trash me-1"></i>
                                                    Ջնջել
                                                </button>

                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

<x-modal-delete></x-modal-delete>
