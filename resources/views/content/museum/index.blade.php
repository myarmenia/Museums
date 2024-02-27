@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')
@section('page-script')
    <script src="{{ asset('assets/js/change-status.js') }}"></script>
    <script src="{{ asset('assets/js/delete-item.js') }}"></script>
@endsection

@section('content')
    @include('includes.alert')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Թանգարաններ /</span> Ցանկ
    </h4>
    <div class="card">

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-header">Թանգարանների ցանկ</h5>
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
                            <th>Դիտել</th>
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
                                    <a class="dropdown-item d-flex justify-content-center" href="#">
                                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M16 7C6 7 2 16 2 16C2 16 6 25 16 25C26 25 30 16 30 16C30 16 26 7 16 7Z" stroke="#49536E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M16 21C18.7614 21 21 18.7614 21 16C21 13.2386 18.7614 11 16 11C13.2386 11 11 13.2386 11 16C11 18.7614 13.2386 21 16 21Z" stroke="#49536E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </a>
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

