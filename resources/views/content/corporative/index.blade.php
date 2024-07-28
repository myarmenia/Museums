@extends('layouts/contentNavbarLayout')

@section('title', 'Կորպորատիվ - Ցանկ')

@section('page-script')
    <script src="{{ asset('assets/js/delete-item.js') }}"></script>
@endsection

@section('content')
    @include('includes.alert')
    <h4 class="py-3 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);">Կորպորատիվ</a>
                </li>
                <li class="breadcrumb-item active">Ցանկ</li>
            </ol>
        </nav>
    </h4>
    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-header">Կորպորատիվի ցանկ</h5>
            </div>

            <div>
                <a href="{{ route('corporative.create') }}" class="btn btn-primary mx-4">Ստեղծել նոր կորպորատիվ </a>
            </div>

        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <form action="{{ route('corporative') }}" method="get" class="row g-3 mt-2" style="display: flex">
                    <div class="mb-3 justify-content-end" style="display: flex; gap: 8px">
                        <div class="col-2">
                            <input type="text" class="form-control" id="" placeholder="ՀՎՀՀ" name="tin"
                                value="{{ request()->input('tin') }}">
                        </div>
                        <div class="mb-3 row">
                        </div>
                        <button class="btn btn-primary col-2">Փնտրել</button>
                    </div>
                </form>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Անուն</th>
                            <th>ՀՎՀՀ</th>
                            <th>Ֆայլ</th>
                            <th>Էլ․ հասցե</th>
                            <th>Պայմանագրի համար</th>
                            <th>Տոմսերի քանակ</th>
                            <th>Մնացած տոմսեր</th>
                            <th>Գին</th>
                            <th>Ակտիվ է մինչև</th>
                            <th>Փոփոխել</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $corporative)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $corporative->name }}</td>
                                <td>{{ $corporative->tin }}</td>
                                <td>
                                    @if ($corporative->file_path)
                                        <a href="{{ route('get-file', ['path' => $corporative->file_path]) }}"
                                            target="_blank">
                                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M16 7C6 7 2 16 2 16C2 16 6 25 16 25C26 25 30 16 30 16C30 16 26 7 16 7Z"
                                                    stroke="#49536E" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M16 21C18.7614 21 21 18.7614 21 16C21 13.2386 18.7614 11 16 11C13.2386 11 11 13.2386 11 16C11 18.7614 13.2386 21 16 21Z"
                                                    stroke="#49536E" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </a>
                                    @endif
                                </td>
                                <td>{{ $corporative->email }}</td>
                                <td>{{ $corporative->contract_number }}</td>
                                <td>{{ $corporative->tickets_count }}</td>
                                <td>{{ (int) $corporative->tickets_count - (int) $corporative->visitors_count }}</td>
                                <td>{{ $corporative->price }}</td>
                                <td>{{ $corporative->ttl_at }}</td>
                                <td class="text-center">
                                    @if($corporative->created_at->addHour() >= Carbon\Carbon::now())
                                        <a  href="{{route('corporative_edit', $corporative->id)}}"><i
                                            class="bx bx-edit-alt me-1"></i>
                                        </a>
                                    @else
                                        <i class="bx bx-edit-alt me-1"></i>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="demo-inline-spacing">
                {{ $data->links() }}
            </div>
        </div>
    </div>
@endsection

<x-modal-delete></x-modal-delete>
