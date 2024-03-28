@extends('layouts/contentNavbarLayout')

@section('title', 'Ապրանքների - Դրամարկղ')

@section('page-script')
    <script src="{{ asset('assets/js/admin\cashier\productCashier.js') }}"></script>
@endsection

@section('content')
    @include('includes.alert')
    <h4 class="py-3 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard-analytics') }}">Դրամարկղ</a>
                </li>
                <li class="breadcrumb-item active">Ապրանք</li>
            </ol>
        </nav>
    </h4>

    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="card-header">Ապրանք</h4>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('cashier.product') }}" method="get" class="row g-3 mt-2" style="display: flex">
                <div class="d-flex justify-content-end">
                    <div class="mx-2">
                        <select id="defaultSelect" name="product_category_id" class="form-select"
                            value="{{ request()->input('product_category_id') }}">
                            <option value="">ֆիլտրել ըստ կատեգորիաի</option>
                            @foreach ($product_category as $item)
                                @if (request()->input('product_category_id') != null && $item->id == request()->input('product_category_id'))
                                    <option value="{{ $item->id }}" selected>
                                        {{ __('product-categories.' . $item->key) }}</option>
                                @else
                                    <option value="{{ $item->id }}">{{ __('product-categories.' . $item->key) }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-primary">Փնտրել</button>
                </div>
        </div>
        </form>
        <form action="{{ route('cashier.add.product') }}" method="post">

        <div class="table-responsive text-nowrap">
            <table class="table cashier-table">
                <thead>
                    <tr>
                        <th>Անուն</th>
                        <th>Մնացել է</th>
                        <th>Քանակ</th>
                        <th>Արժեք</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($data as $item)
                        {{-- @dd($item->translation('am')) --}}
                        <tr class='table-default'>
                            <td>{{ $item->translation('am')->name }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td><input type="number" min="0" min_quantity={{ $item['min_quantity'] }}
                                    max_quantity={{ $item['max_quantity'] }} class="form-control" onwheel="return false;"
                                    price="<?= $item['price'] ?>" id="product_{{ $item['id'] }}"
                                    name="product[{{ $item['id'] }}]"></td>
                            <td id = 'product-ticket-price_{{ $item['id'] }}'>0</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end">
            <div class="d-flex">
                <div class="me-3">Ընդհանուր</div>
                <div class="me-2">
                    <span id="product-total-count">0</span>
                    <span>տոմս</span>
                </div>
                <div class="me-2">
                    <span id="product-total-price">0</span>
                    <span>դրամ</span>
                </div>
            </div>
        </div>
        <div class="mt-3 row justify-content-end">
            <div class="col-sm-10 d-flex justify-content-end">
                <button id='product-button' type="submit" class="btn btn-primary">Պահպանել</button>
            </div>
        </div>
        </form>

            <div class="demo-inline-spacing">
                {{ $data->links() }}
            </div>
    </div>
    </div>


@endsection
