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
        <span class="text-muted fw-light">Դրամարկղ /</span> Ստեղծել նոր տոմս
    </h4>
    <div class="card">

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-header">Ստեղծել նոր տոմս</h5>
            </div>
        </div>
        <div class="card-body">

            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                        data-bs-target="#navs-top-home" aria-controls="navs-top-home" aria-selected="true">Տոմս</button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                        data-bs-target="#navs-top-educational" aria-controls="navs-top-educational"
                        aria-selected="false">Կրթական</button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                        data-bs-target="#navs-top-event" aria-controls="navs-top-event"
                        aria-selected="false">Միջոցառում</button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                        data-bs-target="#navs-top-aboniment" aria-controls="navs-top-aboniment"
                        aria-selected="false">Աբոնիմենտ</button>
                </li>
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
                                        <td><input type="number" min="0" class="form-control" onwheel="return false;" price="<?=$data['ticket']['price']?>"
                                                id="standard-ticket" name="standard-ticket"
                                                value="{{ old('standard-ticket') }}"></td>
                                        <td id = 'standard-ticket-price'>0</td>
                                    </tr>
                                    <tr class='table-default'>
                                        <td>Զեղչված</td>
                                        <td><input type="number" min="0" class="form-control" onwheel="return false;" price="<?=$data['ticket']['sale']?>" 
                                                id="sale-ticket" name="sale-ticket" value="{{ old('sale-ticket') }}"></td>
                                        <td id = 'sale-ticket-price'>0</td>
                                    </tr>
                                    <tr class='table-default'>
                                        <td>Անվճար</td>
                                        <td><input type="number" min="0" class="form-control" id="free-ticket" onwheel="return false;"
                                                name="free-ticket" value="{{ old('free-ticket') }}"></td>
                                        <td>0</td>
                                    </tr>
                                </tbody>

                                <tbody class="table-border-bottom-0" style="border-top: 30px solid transparent">
                                    <tr class='table-default'>
                                        <td>Էքսկուրսավար(հայերեն)</td>
                                        <td><input type="number"  onwheel="return false;" price="<?=$data['ticket']['guid-arm']?>"  min="0" class="form-control"
                                                id="git-arm" name="git-arm" value="{{ old('git-arm') }}"></td>
                                        <td id = 'git-arm-price'>0</td>
                                    </tr>
                                    <tr class='table-default'>
                                        <td>Էքսկուրսավար(այլ)</td>
                                        <td><input type="number"  onwheel="return false;" price="<?=$data['ticket']['guid-other']?>"  min="0" class="form-control"
                                                id="git-other" name="git-other" value="{{ old('git-other') }}"></td>
                                        <td id = 'git-other-price'>0</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end">
                            <div class="d-flex">
                                <div class="me-3">Ընդհանուր</div>
                                <div class="me-2">
                                    <span id="ticket-total-count">0</span>
                                    <span>տոմս</span>
                                </div>
                                <div class="me-2">
                                    <span id="git-total-count">0</span>
                                    <span>Էքսկուրսավար</span>
                                </div>
                                <div class="me-2">
                                    <span id="ticket-total-price">0</span>
                                    <span>դրամ</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 row justify-content-end">
                            <div class="col-sm-10 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Պահպանել</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="navs-top-educational" role="tabpanel">
                    <form action="{{ route('cashier.add.ticket') }}" method="post">
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
                                    @foreach ($data['educational'] as $item)
                                        <tr class='table-default'>
                                            {{-- @dd($item) --}}
                                            <td>{{ $item['name'] }}</td>
                                            <td><input type="number" min="0" class="form-control" onwheel="return false;" price="<?=$item['price']?>"
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
                                <button type="submit" class="btn btn-primary">Պահպանել</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="navs-top-event" role="tabpanel">
                    <form action="{{ route('cashier.add.ticket') }}" method="post">
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
                                    @foreach ($data['educational'] as $item)
                                        <tr class='table-default'>
                                            <td>{{ $item['name'] }}</td>
                                            <td><input type="number" min="0" class="form-control" onwheel="return false;" price="<?=$item['price']?>"
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
                                <button type="submit" class="btn btn-primary">Պահպանել</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="navs-top-aboniment" role="tabpanel">
                    <form action="{{ route('cashier.add.ticket') }}" method="post">
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
                                        <td>Աբոնիմենտ</td>
                                        <td><input type="number" min="0" class="form-control" onwheel="return false;" price="<?=$data['aboniment']['price']?>"
                                                id="aboniment-ticket" name="aboniment-ticket"
                                                value="{{ old('aboniment-ticket') }}"></td>
                                        <td id = 'aboniment-ticket-price'>0</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3 row justify-content-end">
                            <div class="col-sm-10 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Պահպանել</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="navs-top-corporative" role="tabpanel">
                    <form action="{{ route('cashier.buy.corporative') }}" method="post">
                        <div class="table-responsive text-nowrap">
                            <div class="d-flex">
                                <input type="text" class="form-control" id="corporative-coupon-input"
                                   placeholder="Գրեք կուպոնը"  name="corporative-ticket">
                                <button type="button" id = "corporative-coupon" class="btn btn-primary ms-2">ստուգել</button>
                            </div>
                            
                        </div>
                        <div id='corporative-sale' class="mt-3 d-none">
                            <div>
                                <label for="corporative-ticket-price">Ընկերության անուն - <span id="corporative-name"></span></label>
                            </div>
                            <div>
                                <label for="corporative-ticket-price">Մնացած տոմսերի քանակ - <span id="count-corporative-ticket"></span></label>
                            </div>

                            <div class="mt-2">
                                <label for="corporative-ticket-price">Քանակ</label>
                                <input type="number" min="0" name='buy-ticket' class="form-control" onwheel="return false;" />
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























            {{-- <div class="row">
                    <div class="col-md mb-4 mb-md-2">
                        <div class="accordion mt-3" id="accordionExample">
                            <div class="card accordion-item active">
                                <h2 class="accordion-header" id="headingOne">
                                    <button type="button" class="accordion-button" data-bs-toggle="collapse"
                                        data-bs-target="#accordionOne" aria-expanded="true" aria-controls="accordionOne">
                                        Accordion Item 1
                                    </button>
                                </h2>

                                <div id="accordionOne" class="accordion-collapse collapse show"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        Lemon drops chocolate cake gummies carrot cake chupa chups muffin topping. Sesame
                                        snaps icing marzipan gummi
                                        bears macaroon dragée danish caramels powder. Bear claw dragée pastry topping
                                        soufflé. Wafer gummi bears
                                        marshmallow pastry pie.
                                    </div>
                                </div>
                            </div>
                            <div class="card accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                                        data-bs-target="#accordionTwo" aria-expanded="false" aria-controls="accordionTwo">
                                        Accordion Item 2
                                    </button>
                                </h2>
                                <div id="accordionTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        Dessert ice cream donut oat cake jelly-o pie sugar plum cheesecake. Bear claw dragée
                                        oat cake dragée ice
                                        cream halvah tootsie roll. Danish cake oat cake pie macaroon tart donut gummies.
                                        Jelly beans candy canes
                                        carrot cake. Fruitcake chocolate chupa chups.
                                    </div>
                                </div>
                            </div>
                            <div class="card accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                                        data-bs-target="#accordionThree" aria-expanded="false"
                                        aria-controls="accordionThree">
                                        Accordion Item 3
                                    </button>
                                </h2>
                                <div id="accordionThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        Oat cake toffee chocolate bar jujubes. Marshmallow brownie lemon drops cheesecake.
                                        Bonbon gingerbread
                                        marshmallow sweet jelly beans muffin. Sweet roll bear claw candy canes oat cake
                                        dragée caramels. Ice cream
                                        wafer danish cookie caramels muffin.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <small class="text-light fw-medium">Accordion Without Arrow</small>
                        <div id="accordionIcon" class="accordion mt-3 accordion-without-arrow">
                            <div class="accordion-item card">
                                <h2 class="accordion-header text-body d-flex justify-content-between"
                                    id="accordionIconOne">
                                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                                        data-bs-target="#accordionIcon-1" aria-controls="accordionIcon-1">
                                        Accordion Item 1
                                    </button>
                                </h2>

                                <div id="accordionIcon-1" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionIcon">
                                    <div class="accordion-body">
                                        Lemon drops chocolate cake gummies carrot cake chupa chups muffin topping. Sesame
                                        snaps icing marzipan gummi
                                        bears macaroon dragée danish caramels powder. Bear claw dragée pastry topping
                                        soufflé. Wafer gummi bears
                                        marshmallow pastry pie.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item card">
                                <h2 class="accordion-header text-body d-flex justify-content-between"
                                    id="accordionIconTwo">
                                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                                        data-bs-target="#accordionIcon-2" aria-controls="accordionIcon-2">
                                        Accordion Item 2
                                    </button>
                                </h2>
                                <div id="accordionIcon-2" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionIcon">
                                    <div class="accordion-body">
                                        Dessert ice cream donut oat cake jelly-o pie sugar plum cheesecake. Bear claw dragée
                                        oat cake dragée ice
                                        cream halvah tootsie roll. Danish cake oat cake pie macaroon tart donut gummies.
                                        Jelly beans candy canes
                                        carrot cake. Fruitcake chocolate chupa chups.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item card active">
                                <h2 class="accordion-header text-body d-flex justify-content-between"
                                    id="accordionIconThree">
                                    <button type="button" class="accordion-button" data-bs-toggle="collapse"
                                        data-bs-target="#accordionIcon-3" aria-expanded="true"
                                        aria-controls="accordionIcon-3">
                                        Accordion Item 3
                                    </button>
                                </h2>
                                <div id="accordionIcon-3" class="accordion-collapse collapse show"
                                    data-bs-parent="#accordionIcon">
                                    <div class="accordion-body">
                                        Oat cake toffee chocolate bar jujubes. Marshmallow brownie lemon drops cheesecake.
                                        Bonbon gingerbread
                                        marshmallow sweet jelly beans muffin. Sweet roll bear claw candy canes oat cake
                                        dragée caramels. Ice cream
                                        wafer danish cookie caramels muffin.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            {{-- @foreach (languages() as $lang)
                    <div class="mb-3 row">
                        <label for="name-{{ $lang }}" class="col-md-2 col-form-label">Անվանում
                            ({{ $lang }})<span class="required-field">*</span>
                        </label>

                        <div class="col-md-10">
                            <input class="form-control" placeholder="Անվանում {{ languagesName($lang) }}ով"
                                value="{{ old("name.$lang") }}" id="name-{{ $lang }}"
                                name="name[{{ $lang }}]" />
                        </div>
                    </div>
                    @error("name.$lang")
                        <div class="mb-3 row justify-content-end">
                            <div class="col-sm-10 text-danger fts-14">{{ $message }}
                            </div>
                        </div>
                    @enderror
                @endforeach

                @foreach (languages() as $lang)
                    <div class="mb-3 row">
                        <label for="description-{{ $lang }}" class="col-md-2 col-form-label">Նկարագրություն
                            ({{ $lang }})<span class="required-field">*</span>
                        </label>

                        <div class="col-md-10">
                            <textarea class="form-control" id="description-{{ $lang }}" rows="3" name="description[{{ $lang }}]" > {{ old("description.$lang") }}</textarea>
                        </div>
                    </div>
                    @error("description.$lang")
                        <div class="mb-3 row justify-content-end">
                            <div class="col-sm-10 text-danger fts-14">{{ $message }}
                            </div>
                        </div>
                    @enderror
                @endforeach

                @foreach (languages() as $lang)
                    <div class="mb-3 row">
                        <label for="address-{{ $lang }}" class="col-md-2 col-form-label">Հասցե
                            ({{ $lang }})<span class="required-field">*</span>
                        </label>

                        <div class="col-md-10">
                            <input class="form-control" placeholder="Հասցեն {{ languagesName($lang) }}ով"
                                value="{{ old("address.$lang") }}" id="address-{{ $lang }}"
                                name="address[{{ $lang }}]" />
                        </div>
                    </div>
                    @error("address.$lang")
                        <div class="mb-3 row justify-content-end">
                            <div class="col-sm-10 text-danger fts-14">{{ $message }}
                            </div>
                        </div>
                    @enderror
                @endforeach

                @foreach (languages() as $lang)
                    <div class="mb-3 row">
                        <label for="work_days-{{ $lang }}" class="col-md-2 col-form-label">Աշխատանքային օրեր
                            ({{ $lang }})<span class="required-field">*</span>
                        </label>

                        <div class="col-md-10">
                            <input class="form-control" placeholder="Աշխատանքային օրերը {{ languagesName($lang) }}ով"
                                value="{{ trans('museum.day-hours', [], $lang) }}" id="work_days-{{ $lang }}"
                                name="work_days[{{ $lang }}]" />
                        </div>
                    </div>
                    @error("work_days.$lang")
                        <div class="mb-3 row justify-content-end">
                            <div class="col-sm-10 text-danger fts-14">{{ $message }}
                            </div>
                        </div>
                    @enderror
                @endforeach

                @foreach (languages() as $lang)
                    <div class="mb-3 row">
                        <label for="owner-{{ $lang }}" class="col-md-2 col-form-label">Տնօրենի անուն
                            ազգանուն({{ $lang }})<span class="required-field">*</span>
                        </label>

                        <div class="col-md-10">
                            <input class="form-control" placeholder="Տնօրենի անուն ազգանուն {{ languagesName($lang) }}ով"
                                value="{{ old("owner.$lang") }}" id="owner-{{ $lang }}"
                                name="owner[{{ $lang }}]" />
                        </div>
                    </div>
                    @error("owner.$lang")
                        <div class="mb-3 row justify-content-end">
                            <div class="col-sm-10 text-danger fts-14">{{ $message }}
                            </div>
                        </div>
                    @enderror
                @endforeach

                @foreach (museumPhoneCount() as $idx => $phone)
                    <div class="mb-3 row">
                        <label for="phones-{{ $phone }}" class="col-md-2 col-form-label">Թանգարանի հեռախոսահամար {{$idx+1}}
                            @if ($idx == 0)
                                <span class="required-field">*</span>
                            @endif
                        </label>

                        <div class="col-md-10">
                            <input class="form-control" placeholder="Թանգարանի հեռախոսահամար {{$idx+1}}"
                                value="{{ old("phones.$phone") }}" id="phones-{{ $phone }}"
                                name="phones[{{ $phone }}]" />
                        </div>
                    </div>
                    @error("phones.$phone")
                        <div class="mb-3 row justify-content-end">
                            <div class="col-sm-10 text-danger fts-14">{{ $message }}
                            </div>
                        </div>
                    @enderror
                @endforeach
                
                <div class="mb-3 row">
                    <label for="region" class="col-md-2 col-form-label">Մարզ <span class="required-field">*</span></label>
                    <div class="col-md-10">
                        <select id="defaultSelect" name="region" class="form-select">
                            <option value="">Ընտրեք մարզը</option>
                            @foreach ($regions as $region)
                                <option value="{{ $region->name }}">{{ __('regions.' . $region->name) }}</option>
                            @endforeach
                        </select>
                        @error('region')
                            <div class="justify-content-end">
                                <div class="col-sm-10 text-danger fts-14">{{ $message }}
                                </div>
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="account_number" class="col-md-2 col-form-label">Հաշվեհամար <span
                            class="required-field">*</span></label>
                    <div class="col-md-10">
                        <input class="form-control" placeholder="Հաշվեհամար" value="{{ old('account_number') }}"
                            id="account_number" name="account_number" />
                    </div>
                </div>
                @error('account_number')
                    <div class="mb-3 row justify-content-end">
                        <div class="col-sm-10 text-danger fts-14">{{ $message }}
                        </div>
                    </div>
                @enderror

                <div class="mb-3 row">
                    <label for="email" class="col-md-2 col-form-label">Էլեկտրոնային հասցե</label>
                    <div class="col-md-10">
                        <input class="form-control" placeholder="Էլեկտրոնային հասցե" value="{{ old('email') }}"
                            id="email" name="email" />
                    </div>
                </div>
                @error('email')
                    <div class="mb-3 row justify-content-end">
                        <div class="col-sm-10 text-danger fts-14">{{ $message }}
                        </div>
                    </div>
                @enderror

                @foreach (getLinkType() as $link)
                    <div class="mb-3 row">
                        <label for="link-{{ $link }}" class="col-md-2 col-form-label">{{ getLinkNames($link) }}
                        </label>

                        <div class="col-md-10">
                            <input class="form-control" placeholder="{{ getLinkNames($link) }}-ի հղումը"
                                value="{{ old("link.$link") }}" id="link-{{ $link }}"
                                name="link[{{ $link }}]" />
                        </div>
                    </div>
                    @error("link.$link")
                        <div class="mb-3 row justify-content-end">
                            <div class="col-sm-10 text-danger fts-14">{{ $message }}
                            </div>
                        </div>
                    @enderror
                @endforeach

                <div class="mb-3 row">
                    <label for="photos" class="col-md-2 col-form-label d-flex">
                            Գլխավոր նկար<span
                            class="required-field">*</span>
                        <div class="mx-2" title="Նկարի լայնքը պետք է լինի 1520 մինչև 1550 և բարձրությունը 445 մինչև 500">
                            <svg xmlns="http://www.w3.org/2000/svg"  width="16" height="16" viewBox="0 0 512 512"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM216 336h24V272H216c-13.3 0-24-10.7-24-24s10.7-24 24-24h48c13.3 0 24 10.7 24 24v88h8c13.3 0 24 10.7 24 24s-10.7 24-24 24H216c-13.3 0-24-10.7-24-24s10.7-24 24-24zm40-208a32 32 0 1 1 0 64 32 32 0 1 1 0-64z"/></svg>
                        </div>
                    </label>
                        
                    <div class="col-md-10">
                        <div class="d-flex flex-wrap align-items-start align-items-sm-center">
                            <label for="general_photo" class="btn btn-primary me-2 mb-4" tabindex="0">
                                <span class="d-none d-sm-block">Ավելացնել գլխավոր նկար</span>
                                <i class="bx bx-upload d-block d-sm-none"></i>
                                <input type="file" id="general_photo" name="general_photo" class="account-file-input-general"
                                    hidden accept="image/png, image/jpeg" />
                            </label>
                            <div class="uploaded-images-container uploaded-photo-project" id="uploadedImagesContainerGeneral"></div>
                        </div>
                    </div>
                </div>
                @error('general_photo')
                    <div class="mb-3 row justify-content-end">
                        <div class="col-sm-10 text-danger fts-14" id="photos_div">{{ $message }}
                        </div>
                    </div>
                @enderror

                <div class="mb-3 row">
                    <label for="photos" class="col-md-2 col-form-label d-flex">Նկար
                        <div class="mx-2" title="Նկարների լայնքը պետք է լինի 446 մինչև 460 և բարձրությունը 370 մինչև 380">
                            <svg xmlns="http://www.w3.org/2000/svg"  width="16" height="16" viewBox="0 0 512 512"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM216 336h24V272H216c-13.3 0-24-10.7-24-24s10.7-24 24-24h48c13.3 0 24 10.7 24 24v88h8c13.3 0 24 10.7 24 24s-10.7 24-24 24H216c-13.3 0-24-10.7-24-24s10.7-24 24-24zm40-208a32 32 0 1 1 0 64 32 32 0 1 1 0-64z"/></svg>
                        </div>
                    </label>
                    <div class="col-md-10">
                        <div class="d-flex flex-wrap align-items-start align-items-sm-center">
                            <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                <span class="d-none d-sm-block">Ավելացնել նոր նկար</span>
                                <i class="bx bx-upload d-block d-sm-none"></i>
                                <input type="file" id="upload" name="photos[]" class="account-file-input" multiple
                                    hidden accept="image/png, image/jpeg" />
                            </label>
                            <div class="uploaded-images-container uploaded-photo-project" id="uploadedImagesContainer">
                            </div>
                        </div>
                    </div>
                </div>
                @error('photos.*')
                    <div class="mb-3 row justify-content-end">
                        <div class="col-sm-10 text-danger fts-14" id="photos_div">{{ $message }}
                        </div>
                    </div>
                @enderror --}}


        </div>
    </div>


    </div>


@endsection
