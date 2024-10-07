@extends('layouts/contentNavbarLayout')

@section('title', 'Լոգավորում - Ցանկ')
@section('page-script')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="{{ asset('assets/js/admin/logs/show-details.js') }}"></script>
@endsection

@section('content')

    <h4 class="py-3 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);">Լոգավորում</a>
                </li>
                <li class="breadcrumb-item active">Ցանկ</li>
            </ol>
        </nav>
    </h4>
    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-header">Գործողությունների ցանկ</h5>
            </div>

        </div>
        <div class="card-body">

            <div>
                <form action="{{route('cashier_logs')}}" method="get" class="row g-3 mt-2" style="display: flex">
                    <div class="mb-3 justify-content-end" style="display: flex; gap: 8px">


                        <div class="col-2">
                            <select id="defaultSelect" name="type" class="form-select" value="{{ request()->input('type') }}" >
                                    <option value="" disabled selected>Գործ․ տեսակ</option>
                                    <option value="" >{{__('logs.all')}}</option>
                                    <option value="store" {{ request()->input('type') == 'store' ? 'selected' : '' }}>{{__('logs.store')}}</option>
                                    <option value="return" {{ request()->input('type') == 'return' ? 'selected' : '' }}>{{__('logs.return')}}</option>
                            </select>
                        </div>

                        <div class="col-2">
                            <input type="date" class="form-control" id="datefrom" placeholder="Ստեղծման ամսաթիվ" name="from_created_at" value="{{ request()->input('from_created_at') }}">
                        </div>

                        <div class="col-2">
                            <input type="date" class="form-control" id="dateto" placeholder="Ստեղծման ամսաթիվ" name="to_created_at" value="{{ request()->input('to_created_at') }}">
                        </div>

                        <button class="btn btn-primary col-2">Որոնել</button>
                        <a class="btn btn-primary" href="{{ route('cashier_logs') }}">Չեղարկել</a>
                    </div>
                </form>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Օգտագործող</th>
                            <th>Գործ․ տեսակ</th>
                            <th>Ամսաթիվ</th>
                            <th> Դիտել</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $log)

                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $log->user->name }} {{ $log->user->surname }}</td>
                                <td>{{ __("logs.$log->action") }}</td>
                                <td>{{ $log->created_at->format('d-m-Y')}}</td>
                                <td><a href="" class="menu-link show_details"
                                        data-id="{{$log->id}}">
                                            <i class="menu-icon tf-icons bx bx-show-alt"></i>
                                    </a></td>
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

    <div class="show-more-details" data-bs-toggle="modal" data-bs-target="#showDetails"></div>

@endsection


<div class="show-details-component">
  <x-show-details :details="isset($details) ? $details : null " ></x-show-details>
</div>
