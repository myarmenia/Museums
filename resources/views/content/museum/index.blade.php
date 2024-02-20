@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')
@section('page-script')
    <script src="{{ asset('assets/js/change-status.js') }}"></script>
    <script src="{{ asset('assets/js/delete-item.js') }}"></script>
@endsection

@section('content')
    @include('includes.alert')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Թանգարաններ /</span> Ցուցակ
    </h4>
    <div class="card">

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-header">Թանգարանների ցուցակ</h5>
            </div>
            <div>
                <a href="{{ route('create-museum') }}" class="btn btn-primary mx-4">Ավելացնել նոր թանգարան</a>
            </div>
        </div>
        <div class="card-body">

            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Թանգ․ անուն</th>
                            <th>Տնօրեն</th>
                            <th>Էլ․ հասցե</th>
                            <th>Ստեղծված է</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $museum)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $museum->translationsAdmin->first()->name}}</td>
                                <td>{{ $museum->user->name}}</td>
                                <td>{{ $museum->email}}</td>
                                <td>{{ $museum->created_at }}</td>
                                <td>
                                    <div class="dropdown action" data-id="{{ $museum->id }}" data-tb-name="museums">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        {{-- <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{route('museum.edit', $museum->id)}}"><i
                                                    class="bx bx-edit-alt me-1"></i>Редактировать</a>
                                            <button type="button" class="dropdown-item click_delete_item"
                                                data-bs-toggle="modal" data-bs-target="#smallModal"><i
                                                    class="bx bx-trash me-1"></i>
                                                Удалить</button>
                                        </div> --}}
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

