@extends('layouts/contentNavbarLayout')

@section('title', 'Ապրանք - Վաճառք')

@section('page-script')
    <script src="{{ asset('assets/js/admin\cashier\cashier.js') }}"></script>
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/cashier/cashier.css') }}">
@endsection

@section('content')
    @include('includes.alert')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Դրամարկղ /</span> Վաճառել ապրանք
    </h4>
    <div class="card">

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-header">Վաճառել ապրանք</h5>
            </div>
        </div>
        <div class="card-body">

            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                        data-bs-target="#navs-top-educational" aria-controls="navs-top-educational"
                        aria-selected="false">Կրթական</button>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade" id="navs-top-educational" role="tabpanel">
                    <form action="{{ route('cashier.add.educational') }}" method="post">
                        <div class="table-responsive text-nowrap">
                            <table class="table cashier-table">
                                <thead>
                                    <tr>
                                        <th>Անուն</th>
                                        <th>Մասնակիցների միջակայք</th>
                                        <th>Քանակ</th>
                                        <th>Արժեք</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    <div id='educational-error' class='d-none' style="color:red">Տոմսերի քանակը պետք է համապատասխանի միջակայքին</div>
                                    @foreach ($data['educational'] as $item)
                                        <tr class='table-default'>
                                            <td>{{ $item['name'] }}</td>
                                            <td>{{ $item['min_quantity'] . '-' . $item['max_quantity'] }}</td>
                                            <td><input type="number" min="0" min_quantity={{$item['min_quantity']}} max_quantity={{$item['max_quantity']}} class="form-control" onwheel="return false;" price="<?=$item['price']?>"
                                                    id="educational_{{ $item['id'] }}" name="educational[{{ $item['id'] }}]"></td>
                                            <td id = 'educational-ticket-price_{{ $item['id'] }}'>0</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end">
                            <div class="d-flex">
                                <div class="me-3">Ընդհանուր</div>
                                <div class="me-2">
                                    <span id="educational-total-count">0</span>
                                    <span>տոմս</span>
                                </div>
                                <div class="me-2">
                                    <span id="educational-total-price">0</span>
                                    <span>դրամ</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 row justify-content-end">
                            <div class="col-sm-10 d-flex justify-content-end">
                                <button id='educational-button' type="submit" class="btn btn-primary">Պահպանել</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    </div>


@endsection
