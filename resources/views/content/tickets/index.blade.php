@extends('layouts/contentNavbarLayout')
@section('page-script')
    <script src="{{ asset('assets/js/admin/ticket/index.js') }}"></script>
@endsection
@section('content')
  @include('includes.alert')
  <div class="message">
    @if (!museumAccessId())
        <div class="alert alert-danger"> Նախ ստեղծեք թանգարան </div>
    @endif
  </div>

  <h4 class="py-3 mb-4">
      <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
              <li class="breadcrumb-item">
                  <a href="{{route('educational_programs_list')}}">Տոմսեր</a>
              </li>
          </ol>
      </nav>
  </h4>
  <div class="row ">
      <div class="col">
          <div class="card my-3">
              <div class="d-flex justify-content-between align-items-center">
                  <div>
                      <h6 class="card-header">Ստանդարտ տոմս</h6>
                  </div>
              </div>
              <div class="card-body">

                  <form action="{{ $ticket_standart == null ? route('ticket_standart_store') : route('ticket_standart_update', $ticket_standart->id)}}" class="ticket_settings">

                      <div class="mb-3">
                          <label for="standart_price" class="col col-form-label">Գին <span class="required-field text-danger">*</span></label>
                          <input class="form-control" placeholder="Գին" value="{{ $ticket_standart != null ? $ticket_standart->price : '' }}" id="standart_price" name="price" />
                      </div>
                     <button type="submit" class="btn btn-primary">Պահպանել</button>
                  </form>
              </div>
          </div>
      </div>
      <div class="col">
          <div class="card my-3">
              <div class="d-flex justify-content-between align-items-center">
                  <div>
                      <h6 class="card-header">Աբոնեմենտ</h6>
                  </div>
              </div>
              <div class="card-body">
                  <form action="{{ $ticket_subscription == null ? route('ticket_subscription_store') : route('ticket_subscription_update', $ticket_subscription->id)}}" class="ticket_settings">
                      <div class="mb-3 row">
                          <label for="subscription_price" class="col col-form-label">Գին <span class="required-field text-danger">*</span></label>
                          <input class="form-control" placeholder="Գին" value="{{ $ticket_subscription != null ? $ticket_subscription->price : '' }}" id="subscription_price" name="price" />
                      </div>
                      <div class="mb-3 row">
                            <div class="col-md-2 form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="status" {{$ticket_subscription != null && $ticket_subscription->status ? 'checked' : ''}} name="status">
                                <label class="form-check-label" for="status">Կարգավիճակ</label>
                            </div>
                      </div>

                      <button type="submit" class="btn btn-primary">Պահպանել</button>

                  </form>
              </div>
          </div>
      </div>

      <div class="col">
          <div class="card my-3">
              <div class="d-flex justify-content-between align-items-center">
                  <div>
                      <h6 class="card-header">Էքսկուրսավար</h6>
                  </div>
              </div>
              <div class="card-body">

                  <form action="{{ $guide_service == null ? route('guide_service_store') : route('guide_service_update', $guide_service->id)}}" method="post" class="ticket_settings">

                      <div class="mb-3 row">
                          <label for="price_am" class="col col-form-label">Գինը հայերեն լեզվի համար <span class="required-field text-danger">*</span></label>
                          <input class="form-control" placeholder="Գինը հայերեն լեզվի համար" value="{{ $guide_service != null ? $guide_service->price_am : '' }}" id="price_am" name="price_am" />
                      </div>

                      <div class="mb-3 row">
                          <label for="price_other" class="col col-form-label">Գինը օտար լեզվի համար<span class="required-field text-danger">*</span></label>
                          <input class="form-control" placeholder="Գինը օտար լեզվի համար" value="{{ $guide_service != null ? $guide_service->price_other : '' }}" id="price_other" name="price_other" />
                      </div>

                      <button type="submit" class="btn btn-primary">Պահպանել</button>

                  </form>
              </div>
          </div>
      </div>

  </div>

@endsection
