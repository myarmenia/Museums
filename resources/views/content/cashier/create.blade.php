@extends('layouts/contentNavbarLayout')

@section('title', 'Տոմսի - Ստեղծում')

@section('page-script')
    <script src="{{ asset('assets/js/admin\cashier\cashier.js') }}"></script>
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/cashier/cashier.css') }}">
@endsection

@section('content')
    @include('includes.alert')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Դրամարկղ /</span> Տոմսերի վաճառք
    </h4>
    <input type="hidden" id="pdf-path" value="{{ session('pdfFile') }}">
    <div class="card">

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-header">Տոմսերի վաճառք</h5>
            </div>
        </div>
        <div class="card-body">

            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                        data-bs-target="#navs-top-home" aria-controls="navs-top-home" aria-selected="true">Տոմս</button>
                </li>
                @if (count($data['educational']))
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-top-educational" aria-controls="navs-top-educational"
                            aria-selected="false">Կրթական</button>
                    </li>
                @endif
                @if (array_key_exists('events', $data))
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-top-event" aria-controls="navs-top-event"
                            aria-selected="false">Միջոցառում</button>
                    </li>
                @endif
                @if (array_key_exists('aboniment', $data))
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-top-aboniment" aria-controls="navs-top-aboniment"
                            aria-selected="false">Աբոնեմենտ</button>
                    </li>
                @endif
                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                        data-bs-target="#navs-top-corporative" aria-controls="navs-top-corporative"
                        aria-selected="false">Կորպորատիվ</button>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="navs-top-home" role="tabpanel">
                    <form action="{{ route('cashier.add.ticket') }}" method="post">
                        <div class="table-responsive text-nowrap">
                            <table class="table cashier-table">
                                <thead>
                                    <tr>
                                        <th>Տեսակ</th>
                                        <th>Քանակ</th>
                                        <th>Արժեք</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    <tr class='table-default'>
                                        <td>Ստանդարտ</td>
                                        <td><input type="number" min="0" class="form-control form-control-validate"
                                                onwheel="return false;" price="<?= $data['ticket']['price'] ?>"
                                                id="standart" name="standart"
                                                value="{{ old('standart') }}"></td>
                                        <td id = 'standard-ticket-price'>0</td>
                                    </tr>
                                    <tr class='table-default'>
                                        <td>Զեղչված</td>
                                        <td><input type="number" min="0" class="form-control form-control-validate"
                                                onwheel="return false;" price="<?= $data['ticket']['sale'] ?>"
                                                id="discount" name="discount" value="{{ old('discount') }}"></td>
                                        <td id = 'discount-price'>0</td>
                                    </tr>
                                    <tr class='table-default'>
                                        <td>Անվճար</td>
                                        <td><input type="number" min="0" class="form-control form-control-validate" id="free"
                                                onwheel="return false;" name="free" value="{{ old('free') }}">
                                        </td>
                                        <td>0</td>
                                    </tr>
                                </tbody>

                                @if (array_key_exists('guid-arm', $data['ticket']) && array_key_exists('guid-other', $data['ticket']))
                                    <tbody class="table-border-bottom-0" style="border-top: 30px solid transparent">
                                        <tr class='table-default'>
                                            <td>Էքսկուրսավար(հայերեն)</td>
                                            <td><input type="number" onwheel="return false;"
                                                    price="<?= $data['ticket']['guid-arm'] ?>" min="0"
                                                    class="form-control form-control-validate" id="guide_am" name="guide_am"
                                                    value="{{ old('guide_am') }}"></td>
                                            <td id = 'guide_am_price'>0</td>
                                        </tr>
                                        <tr class='table-default'>
                                            <td>Էքսկուրսավար(այլ)</td>
                                            <td><input type="number" onwheel="return false;"
                                                    price="<?= $data['ticket']['guid-other'] ?>" min="0"
                                                    class="form-control form-control-validate" id="guide_other" name="guide_other"
                                                    value="{{ old('guide_other') }}"></td>
                                            <td id = 'guide_other_price'>0</td>
                                        </tr>
                                    </tbody>
                                @endif
                            </table>
                        </div>
                        <div class="d-flex justify-content-end">
                            <div class="d-flex">
                                <div class="me-3">Ընդհանուր</div>
                                <div class="me-2">
                                    <span id="ticket-total-count">0</span>
                                    <span>տոմս</span>
                                </div>
                                @if (array_key_exists('guid-arm', $data['ticket']) && array_key_exists('guid-other', $data['ticket']))
                                    <div class="me-2">
                                        <span id="git-total-count">0</span>
                                        <span>Էքսկուրսավար</span>
                                    </div>
                                @endif
                                <div class="me-2">
                                    <span id="ticket-total-price">0</span>
                                    <span>դրամ</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 row justify-content-end">
                            <div class="col-sm-10 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Տպել</button>
                            </div>
                        </div>
                    </form>
                </div>
                @if (count($data['educational']))
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
                                        <div id='educational-error' class='d-none' style="color:red">Տոմսերի քանակը պետք
                                            է համապատասխանի միջակայքին</div>
                                        @foreach ($data['educational'] as $item)
                                            <tr class='table-default'>
                                                <td>{{ $item['name'] }}</td>
                                                <td>{{ $item['min_quantity'] . '-' . $item['max_quantity'] }}</td>
                                                <td><input type="number" min="0"
                                                        min_quantity={{ $item['min_quantity'] }}
                                                        max_quantity={{ $item['max_quantity'] }} class="form-control form-control-validate"
                                                        onwheel="return false;" price="<?= $item['price'] ?>"
                                                        id="educational_{{ $item['id'] }}"
                                                        name="educational[{{ $item['id'] }}]"></td>
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
                                    <button id='educational-button' type="submit" class="btn btn-primary">Տպել</button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
                @if (array_key_exists('events', $data))
                    <div class="tab-pane fade" id="navs-top-event" role="tabpanel">
                        <form action="{{ route('cashier.add.event') }}" method="post">
                            <div class="table-responsive text-nowrap">
                                <select id="event-select" name="event" class="form-select">
                                    <option value="">Ընտրեք միջոցառումը</option>
                                    @foreach ($data['events'] as $event)
                                        <option value="{{ $event->id }}">{{ $event->translation('am')->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <div id="event-config"> </div>
                            </div>
                            <div id='event-total' class="d-flex justify-content-end d-none">
                                <div class="d-flex">
                                    <div class="me-3">Ընդհանուր</div>
                                    <div class="me-2">
                                        <span id="event-total-count">0</span>
                                        <span>տոմս</span>
                                    </div>
                                    <div class="me-2">
                                        <span id="event-total-price">0</span>
                                        <span>դրամ</span>
                                    </div>
                                </div>
                            </div>
                            <div id="event-save" class="mt-3 row justify-content-end d-none">
                                <div class="col-sm-10 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Տպել</button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
                @if (array_key_exists('aboniment', $data))
                    <div class="tab-pane fade" id="navs-top-aboniment" role="tabpanel">
                        <form action="{{ route('cashier.add.subscription') }}" method="post">
                            <div class="table-responsive text-nowrap">
                                <table class="table cashier-table">
                                    <thead>
                                        <tr>
                                            <th>Անուն</th>
                                            <th>Քանակ</th>
                                            <th>Արժեք</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        <tr class='table-default'>
                                            <td>Աբոնեմենտ</td>
                                            <td><input type="number" min="0" class="form-control form-control-validate"
                                                    onwheel="return false;" price="<?= $data['aboniment']['price'] ?>"
                                                    id="aboniment-ticket" name="aboniment-ticket" max="10"
                                                    value="{{ old('aboniment-ticket') }}"></td>
                                            <td id = 'aboniment-ticket-price'>0</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3 row justify-content-end">
                                <div class="col-sm-10 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Տպել</button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
                <div class="tab-pane fade" id="navs-top-corporative" role="tabpanel">
                    <form action="{{ route('cashier.add.corporative') }}" method="post">
                        <div class="table-responsive text-nowrap">
                            <div class="d-flex">
                                <input type="text" class="form-control" id="corporative-coupon-input"
                                    placeholder="Գրեք կուպոնը" name="corporative-ticket">
                                <button type="button" id = "corporative-coupon"
                                    class="btn btn-primary ms-2">Ստուգել</button>
                            </div>
                        </div>
                        <div id='corporative-coupon-not-found' class="mt-3 d-none"></div>
                        <div id='corporative-sale' class="mt-3 d-none">
                            <div>
                                <label for="corporative-ticket-price">Ընկերության անուն - <span
                                        id="corporative-name"></span></label>
                            </div>
                            <div>
                                <label for="corporative-ticket-price">Մնացած տոմսերի քանակ - <span
                                        id="count-corporative-ticket"></span></label>
                            </div>

                            <div class="mt-2">
                                <label for="corporative-ticket-price">Քանակ</label>
                                <input type="number" min="0" name='buy-ticket' class="form-control form-control-validate"
                                    onwheel="return false;" />
                            </div>

                            <div class="mt-3 row justify-content-end">
                                <div class="col-sm-10 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Հաստատել</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>


    </div>


@endsection
