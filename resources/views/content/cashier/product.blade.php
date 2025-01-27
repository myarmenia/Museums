@extends('layouts/contentNavbarLayout')

@section('title', 'Դրամարկղ - Ապրանքներ')

@section('page-script')
    <script src="{{ asset('assets/js/admin\cashier\productCashier.js') }}"></script>

    <script src="{{ asset('assets/js/ui-modals.js') }}"></script>
@endsection

@section('content')
    @include('includes.alert')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Դրամարկղ /</span> Ապրանք
    </h4>

    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-header">Ապրանք</h5>
            </div>
        </div>

        <div>
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
            </form>
        </div>
        @if ($data->count())
            <form action="{{ route('cashier.add.product') }}" method="post">

                <div class="table-responsive text-nowrap">
                    <table class="table cashier-table">
                        <thead>
                            <tr>
                                <th>Անուն</th>
                                <th>Նկար</th>
                                <th>Մնացել է</th>
                                <th>Քանակ</th>
                                <th>Արժեք</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($data as $item)
                                <tr class='table-default'>
                                    <td>{{ $item->translation('am')->name }}</td>
                                    <td><img width="50" height="50"
                                            src="{{ route('get-file', ['path' => $item->images->first()->path]) }}"></img>
                                    </td>
                                    <td>{{ $item['quantity'] }}</td>
                                    <td><input type="number" min="0" min_quantity={{ $item['min_quantity'] }}
                                            max_quantity={{ $item['max_quantity'] }} class="form-control"
                                            onwheel="return false;" price="<?= $item['price'] ?>"
                                            id="product_{{ $item['id'] }}" name="product[{{ $item['id'] }}]"></td>
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
                            <span>ապրանք</span>
                        </div>
                        <div class="me-2">
                            <span id="product-total-price">0</span>
                            <span>դրամ</span>
                        </div>
                    </div>
                </div>
                <div class="mt-3 row justify-content-end">
                    <div class="col-sm-10 d-flex justify-content-end">
                        <div class="card mb-4">
                            <div>
                                <div class="row gy-3">
                                    <div>
                                        <div>
                                          <div class="mt-3 row  justify-content-end">
                                            <div class="col-sm-4 d-flex justify-content-end align-items-center">
                                              @if(museumHasHdm())
                                                <div class="radioButtons d-flex">
                                                    <div class="form-check">
                                                        <input class="form-check-input productCashierRadio" type="radio" name="cashe"
                                                            value="cash" >
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Կանխիկ
                                                        </label>
                                                    </div>
                                                    <div class="form-check mx-2">
                                                        <input class="form-check-input productCashierRadio" type="radio" name="cashe"
                                                            value="card" >
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            Անկանխիկ
                                                        </label>
                                                    </div>
                                                    <div class="form-check mx-2">
                                                      <input class="form-check-input productCashierRadio" type="radio" name="cashe"
                                                          value="otherPos" >
                                                      <label class="form-check-label" for="flexRadioDefault2">
                                                          Այլ
                                                      </label>
                                                  </div>
                                                </div>
                                                @endif

                                                <button type="submit" {{ museumHasHdm()?"disabled" : null }}
                                                    class="btn btn-primary form-cashier-button mx-2" data-bs-toggle="modal"  data-bs-target="#basicModal"> Վաճառել</button>
                                            </div>
                                        </div>
                                            {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#basicModal">
                                                Վաճառել
                                            </button> --}}
                                            <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel1">Վաճառքի
                                                                հաստատում</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-secondary"
                                                                data-bs-dismiss="modal">Փակել</button>
                                                            <button id='product-button' type="submit"
                                                                class="btn btn-primary m-2">Վաճառել</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="demo-inline-spacing">
                {{ $data->links() }}
            </div>
        @else
            <h3 class="mx-4">Ապրանքներ առկա չեն</h3>
        @endif
    </div>
    </div>


@endsection
