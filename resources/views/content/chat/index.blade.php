@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')
@section('page-script')
    <script src="{{ asset('assets/js/change-status.js') }}"></script>
    <script src="{{ asset('assets/js/delete-item.js') }}"></script>
@endsection

@section('content')
    @include('includes.alert')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Հաղորդագրություններ /</span> Ցանկ
    </h4>
   
    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-header">Հաղորդագրությունների ցանկ</h5>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            @if (!$data)
                <h2 class="p-4">Դեռ չկա առկա նամակներ</h2>
           @else
                <table class="table">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Վերնագիր</th>
                        <th>Այցելու</th>
                        <th>Վերջին նամակ</th>
                        <th>Ստեղծվել է</th>
                        <th>Դիտել</th>
                    </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($data as $key => $room)
                            <tr class="{{$room->read ? 'table-default': 'table-primary'}}">
                                <td>{{ ++$i }}</td>
                                <td>{{ $room->title}}</td>
                                <td>{{ $room->email ?? $room->visitor->email ?? ''}}</td>
                                <td>{{ $room->messages->count()? $room->messages->last()->text : ""}}</td>
                                <td>{{ $room->created_at}}</td>
                                <td>
                                    <a class="dropdown-item d-flex" href="{{ route('room-message', $room->id)}}">
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
            @endif
        </div>
      </div>
      <div class="demo-inline-spacing">
        {{ $data->links() }}
      </div>

@endsection
<x-modal-delete></x-modal-delete>

