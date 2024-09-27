@extends('layouts/contentNavbarLayout')

@section('title', 'Դրամարկղ - Տոմս')

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
                <li data-name='standard' class="nav-item">
                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                        data-bs-target="#navs-top-home" aria-controls="navs-top-home" aria-selected="true">Տոմս</button>
                </li>

                @if (count($data['educational']))
                    <li data-name='educational' class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-top-educational" aria-controls="navs-top-educational"
                            aria-selected="false">Կրթական</button>
                    </li>
                @endif
                @if (array_key_exists('events', $data))
                    <li data-name='events' class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-top-event" aria-controls="navs-top-event"
                            aria-selected="false">Միջոցառում</button>
                    </li>
                @endif
                @if (array_key_exists('aboniment', $data))
                    <li data-name='aboniment' class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-top-aboniment" aria-controls="navs-top-aboniment"
                            aria-selected="false">Անդամակցության քարտ</button>
                    </li>
                @endif
                <li data-name='corporative' class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                        data-bs-target="#navs-top-corporative" aria-controls="navs-top-corporative"
                        aria-selected="false">Կորպորատիվ</button>
                </li>
                @if (array_key_exists('other_services', $data))
                <li data-name='other_services' class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                        data-bs-target="#navs-top-otherService" aria-controls="navs-top-otherService"
                        aria-selected="false">Այլ ծառայություններ</button>
                </li>
              @endif
              @if (array_key_exists('partners', $data))
                  <li data-name='partner' class="nav-item">
                      <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                          data-bs-target="#navs-top-partners" aria-controls="navs-top-partners"
                          aria-selected="false">Գործընկերներ</button>
                  </li>
              @endif
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="navs-top-home" role="tabpanel">
                    <form data-name='standard' class="form-cashier" action="{{ route('cashier.add.ticket') }}" method="post">
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
                                        <td class="remove-value" id = 'standard-ticket-price'>0</td>
                                    </tr>
                                    <tr class='table-default'>
                                        <td>Զեղչված</td>
                                        <td><input type="number" min="0" class="form-control form-control-validate"
                                                onwheel="return false;" price="<?= $data['ticket']['sale'] ?>"
                                                id="discount" name="discount" value="{{ old('discount') }}"></td>
                                        <td class="remove-value" id = 'discount-price'>0</td>
                                    </tr>
                                    <tr class='table-default'>
                                        <td>Անվճար</td>
                                        <td><input type="number" min="0" class="form-control form-control-validate" id="free"
                                                onwheel="return false;" name="free" value="{{ old('free') }}">
                                        </td>
                                        <td class="remove-value" class="remove-value">0</td>
                                    </tr>
                                    <tr class='table-default'>
                                        <td>Դպրոցական</td>
                                        <td><input type="number" min="0" class="form-control form-control-validate" id="school"
                                                onwheel="return false;" name="school" value="{{ old('school') }}">
                                        </td>
                                        <td class="remove-value" class="remove-value"> - </td>
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
                                            <td class="remove-value" id = 'guide_am_price'>0</td>
                                        </tr>
                                        <tr class='table-default'>
                                            <td>Էքսկուրսավար(այլ)</td>
                                            <td><input type="number" onwheel="return false;"
                                                    price="<?= $data['ticket']['guid-other'] ?>" min="0"
                                                    class="form-control form-control-validate" id="guide_other" name="guide_other"
                                                    value="{{ old('guide_other') }}"></td>
                                            <td class="remove-value" id = 'guide_other_price'>0</td>
                                        </tr>
                                    </tbody>
                                @endif
                            </table>
                        </div>
                        <div class="d-flex justify-content-end">
                            <div class="d-flex">
                                <div class="me-3">Ընդհանուր</div>
                                <div class="me-2">
                                    <span class="remove-value" id="ticket-total-count">0</span>
                                    <span>տոմս</span>
                                </div>
                                @if (array_key_exists('guid-arm', $data['ticket']) && array_key_exists('guid-other', $data['ticket']))
                                    <div class="me-2">
                                        <span class="remove-value" id="git-total-count">0</span>
                                        <span>Էքսկուրսավար</span>
                                    </div>
                                @endif
                                <div class="me-2">
                                    <span class="remove-value" id="ticket-total-price">0</span>
                                    <span>դրամ</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 row justify-content-end">
                            <div class="col-sm-10 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary form-cashier-button">Տպել</button>
                            </div>
                        </div>
                    </form>
                </div>
                @if (count($data['educational']))
                    <div class="tab-pane fade" id="navs-top-educational" role="tabpanel">
                        <form data-name='educational' class="form-cashier" action="{{ route('cashier.add.educational') }}" method="post">
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
                                                <td class="remove-value" id = 'educational-ticket-price_{{ $item['id'] }}'>0</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end">
                                <div class="d-flex">
                                    <div class="me-3">Ընդհանուր</div>
                                    <div class="me-2">
                                        <span class="remove-value" id="educational-total-count">0</span>
                                        <span>տոմս</span>
                                    </div>
                                    <div class="me-2">
                                        <span class="remove-value" id="educational-total-price">0</span>
                                        <span>դրամ</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 row justify-content-end">
                                <div class="col-sm-10 d-flex justify-content-end">
                                    <button id='educational-button' type="submit" class="btn btn-primary form-cashier-button">Տպել</button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
                @if (array_key_exists('events', $data))
                    <div class="tab-pane fade" id="navs-top-event" role="tabpanel">
                        <form data-name='events' class="form-cashier" action="{{ route('cashier.add.event') }}" method="post">
                            <div class="table-responsive text-nowrap">
                                <select id="event-select" name="event" class="form-select">
                                    <option value="">Ընտրեք միջոցառումը</option>
                                    @foreach ($data['events'] as $event)
                                        <option value="{{ $event->id }}" {{ session('eventDetailId') == $event->id ? 'selected' : '' }}>{{ $event->translation('am')->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <div id="event-config"> </div>
                            </div>

                              <div id='event-total' class="d-flex justify-content-end d-none">
                                  <div class="d-flex ">
                                      <div class="me-3">Ընդհանուր</div>
                                      <div class="me-2">
                                          <span class="remove-value" id="event-total-count">0</span>
                                          <span>տոմս</span>
                                      </div>
                                      <div class="event-total-cont"></div>
                                      <div class="me-2">
                                          <span class="remove-value" id="event-total-price">0</span>
                                          <span>դրամ</span>
                                      </div>
                                  </div>
                              </div>
                              <div id="event-save" class="mt-3 row justify-content-end d-none">
                                  <div class="col-sm-10 d-flex justify-content-end">
                                      <button type="submit" class="btn btn-primary form-cashier-button">Տպել</button>
                                  </div>
                              </div>

                        </form>
                    </div>
                @endif
                @if (array_key_exists('aboniment', $data))
                    <div class="tab-pane fade" id="navs-top-aboniment" role="tabpanel">
                        <form data-name='aboniment' class="form-cashier" action="{{ route('cashier.add.subscription') }}" method="post">
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
                                            <td>Անդամակցության քարտ</td>
                                            <td><input type="number" min="0" class="form-control form-control-validate"
                                                    onwheel="return false;" price="<?= $data['aboniment']['price'] ?>"
                                                    id="aboniment-ticket" name="aboniment-ticket" max="10"
                                                    value="{{ old('aboniment-ticket') }}"></td>
                                            <td class="remove-value" id = 'aboniment-ticket-price'>0</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3 row justify-content-end">
                                <div class="col-sm-10 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary form-cashier-button">Տպել</button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
                <div class="tab-pane fade" id="navs-top-corporative" role="tabpanel">
                    <form data-name='corporative' class="form-cashier" action="{{ route('cashier.add.corporative') }}" method="post">
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
                                    <button type="submit" class="btn btn-primary form-cashier-button">Հաստատել</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                @if (array_key_exists('other_services', $data))
                  <div class="tab-pane fade" id="navs-top-otherService" role="tabpanel">
                    <form data-name='events' class="form-cashier" action="{{ route('cashier.add.otherServices') }}" method="post">
                        <div class="table-responsive text-nowrap">
                            <select id="otherServices" name="other_service" class="form-select">
                                <option value="">Ընտրեք ծառայությունը</option>
                                @foreach ($data['other_services'] as $service)
                                    <option value="{{ $service->id }}" {{ session('otherServiceId') == $service->id ? 'selected' : '' }}>{{ $service->translation('am')->name }}
                                    </option>
                                @endforeach
                            </select>

                            <div id="other-service-config"> </div>
                        </div>

                          <div id="other-service-save" class="mt-3 row justify-content-end d-none" >
                              <div class="col-sm-10 d-flex justify-content-end">
                                  <button type="submit" class="btn btn-primary form-cashier-button">Տպել</button>
                              </div>
                          </div>
                    </form>
                  </div>
                @endif
                @if (array_key_exists('partners', $data))
                  <div class="tab-pane fade" id="navs-top-partners" role="tabpanel">
                    <form data-name="partner" class="form-cashier" action="{{ route('cashier.add.partner') }}" method="post">
                        <select id="partners" name="partner_id" class="form-select">
                            <option value="">Ընտրեք գործընկերոջը</option>
                            @foreach ($data['partners'] as $partner)
                                <option value = {{ $partner->id }} {{ session('action') == $partner->id ? 'selected' : '' }}>{{ $partner->name }}
                                </option>
                            @endforeach
                        </select>

                        <div id="partner-config"> </div>

                          <div id="other-service-save" class="mt-3 row justify-content-end d-none" >
                              <div class="col-sm-10 d-flex justify-content-end">
                                  <button type="submit" class="btn btn-primary form-cashier-button">Տպել</button>
                              </div>
                          </div>
                          <div id="partnerPrint"  class="d-none">
                            <div id="partner-total" class="d-flex justify-content-end ">
                              <div class="d-flex">
                                  <div class="me-3">Ընդհանուր</div>
                                  <div class="me-2">
                                      <span class="remove-value" id="partner-total-count">0</span>
                                      <span>տոմս</span>
                                  </div>
                                  <div class="me-2">
                                    <span class="remove-value" id="partner-total-guide-count">0</span>
                                    <span>Էքսկուրսավար</span>
                                </div>
                                  <div class="event-total-cont"></div>
                                  <div class="me-2">
                                      <span class="remove-value" id="partner-total-price">0</span>
                                      <span>դրամ</span>
                                  </div>
                              </div>
                            </div>
                            <div id="partner-save" class="mt-3 row justify-content-end ">
                                <div class="col-sm-10 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary form-cashier-button">Տպել</button>
                                </div>
                            </div>
                          </div>

                    </form>
                  </div>
                @endif
            </div>
        </div>
    </div>


    </div>

    <script>
      // Check if the session variable exists and set a JavaScript variable
      console.log("{{\Session::get('open_tab')}}")
      console.log("{{\Session::get('action')}}")
      var isNavsTopTabSet = "{{ session()->has('open_tab') ? \Session::get('open_tab') : false }}";
      var isPartnerId = "{{ session()->has('partnerId') ? \Session::get('partnerId') : false }}";
      var isOtherServiceId = "{{ session()->has('otherServiceId') ? \Session::get('otherServiceId') : false }}";
      var isEventDetailId = "{{ session()->has('eventDetailId') ? \Session::get('eventDetailId') : false }}";
console.log(localStorage)


      document.addEventListener('DOMContentLoaded', function() {
          // Check if the session variable is set
          if (isNavsTopTabSet) {
              // Select the tab link for the #navs-top-partners tab pane
              var tabLink = document.querySelector('.nav-link[data-bs-target="#'+isNavsTopTabSet+'"]');
              var tabPane = document.querySelector('#'+isNavsTopTabSet);

              // Add 'active' class to the selected tab link
              if (tabLink) {
                  tabLink.classList.add('active');
              }

              // Remove 'active' class from other tab links (optional)
              var otherTabLinks = document.querySelectorAll('.nav-link');
              otherTabLinks.forEach(function(link) {
                  if (link !== tabLink) {
                      link.classList.remove('active');
                  }
              });

              var tabPanes = document.querySelectorAll('.tab-pane');
            tabPanes.forEach(function(pane) {
                pane.classList.remove('show', 'active');
                pane.classList.add('fade'); // Optional: keep the fade effect
            });

            // Show the corresponding tab pane and add 'show' and 'active' classes
            if (tabPane) {
                tabPane.classList.add('show', 'active');
                tabPane.classList.remove('fade'); // Remove fade effect for the active pane
            }
          }
      });
    </script>


@endsection
