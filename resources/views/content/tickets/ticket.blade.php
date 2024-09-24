@extends('layouts/contentNavbarLayout')
@section('title', 'Միասնական տոմս')
@section('page-script')
    <script src="{{ asset('assets/js/admin/ticket/new-index.js') }}"></script>
@endsection
@section('content')
  <div class="message"></div>

  <h4 class="py-3 mb-4">
      <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
              <li class="breadcrumb-item">
                  <a href="">Տոմս</a>
              </li>
          </ol>
      </nav>
  </h4>
  <div class="row ">

      <div class="col">
          <div class="card my-3">
              <div class="d-flex justify-content-between align-items-center">
                  <div>
                      <h5 class="card-header">Միասնական տոմս</h5>
                  </div>
              </div>
              <div class="card-body">

                  <form action="{{ $ticket_united == null ? route('ticket_united_store') : route('ticket_united_update', $ticket_united->id)}}" method="post" class="ticket_settings">

                      <div class="mb-3 row">
                          <label for="min_museum_quantity" class="col col-form-label">Թանգարանների նվազագույն քանակ <span class="required-field text-danger">*</span></label>
                          <input class="form-control" placeholder="Թանգարանների նվազագույն քանակ" value="{{ $ticket_united != null ? $ticket_united->min_museum_quantity : '' }}" id="min_museum_quantity" name="min_museum_quantity" />
                      </div>

                      <div class="mb-3 row">
                          <label for="percent" class="col col-form-label">Զեղչի տոկոս<span class="required-field text-danger">*</span></label>
                          <input class="form-control" placeholder="Զեղչի տոկոս " value="{{ $ticket_united != null ? $ticket_united->percent : '' }}" id="percent" name="percent" />
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
                      <h5 class="card-header">Դպրոցական տոմս</h5>
                  </div>
              </div>
              <div class="card-body">

                  <form action="{{ $ticket_school == null ? route('ticket_school_store') : route('ticket_school_update', $ticket_school->id)}}" method="post" class="ticket_settings">

                      <div class="mb-3 row">
                          <label for="price" class="col col-form-label">Դպրոցականների տոմսերի փոխհատուցման արժեք<span class="required-field text-danger"> *</span></label>
                          <input class="form-control" placeholder="Դպրոցականների տոմսերի փոխհատուցման արժեք" value="{{ $ticket_school != null ? $ticket_school->price : '' }}" id="price" name="price" />
                      </div>

                      <button type="submit" class="btn btn-primary">Պահպանել</button>

                  </form>
              </div>
          </div>
      </div>

  </div>

@endsection
