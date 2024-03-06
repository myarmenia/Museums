@extends('layouts/contentNavbarLayout')
@section('page-script')


    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <script src="{{ asset('assets/js/admin/calendar.js') }}"></script>
    {{-- <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'> --}}
    <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/bootstrap5@6.1.10/index.global.min.js'></script>
    {{-- <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.11/locales-all.global.min.js'></script> --}}
    <link href="{{ asset('assets/css/admin/calendar.css') }}" rel='stylesheet' />
@endsection
@section('content')
    <h4 class="py-3 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('educational_programs_list') }}">Կրթական ծրագրեր</a>
                </li>
                <li class="breadcrumb-item active">Ստեղծել նոր ծրագիր</li>

            </ol>
        </nav>
    </h4>


    <div class="row">
        <div class="col-8">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Basic Layout</h5> <small class="text-muted float-end">Default label</small>
                </div>
                <div class="card-body">
                    <div id='calendar'></div>

                </div>
            </div>

        </div>
        <div class="col-4">

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Նոր ամրագրում</h5>
                </div>
                <div class="card-body">
                    <form id="reserve">
                        <div class="mb-3">
                            <label class="form-label" for="educational_program_id">Ծրագրի տեսակը <span class="required-field text-danger">*</span></label>
                            <select id="educational_program_id" name="educational_program_id" class="form-select item">
                                <option value="" disabled selected>Ծրագրի տեսակը</option>

                                @foreach (museumEducationalPrograms() as $item)
                                    <option value="{{ $item->id }}">{{ __('logs.store') }}</option>
                                @endforeach
                                <option value="null_id">Էքսկուրսիա</option>

                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="date">Այցելության օրը <span class="required-field text-danger">*</span></label>
                            <input type="date" class="form-control item" id="date" placeholder="Այցելության օրը"
                                name="date">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="time">Այցելության ժամը <span class="required-field text-danger">*</span></label>
                            <input type="time" class="form-control item" id="time" placeholder="Այցելության ժամը"
                                name="time">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="visitor_quantity">Այցելության քանակը <span class="required-field text-danger">*</span></label>
                            <input type="text" class="form-control item" id="visitor_quantity"
                                placeholder="Այցելության քանակը" name="visitor_quantity">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="description">Մանրամասներ <span class="required-field text-danger">*</span></label>
                            <textarea id="description" class="form-control item" placeholder="Մանրամասներ" name="description"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary ">Ամրագրել</button>
                        <div class="result_message"></div>
                    </form>
                </div>
            </div>
        </div>

    </div>

<x-offcanvas></x-offcanvas>

    {{-- <small class="text-light fw-medium mb-3">Enable Scrolling &amp; Backdrop</small> --}}
    {{-- <div class="col-lg-4 col-md-6">
        <small class="text-light fw-medium mb-3">Enable Scrolling &amp; Backdrop</small>
        <div class="mt-3">
          <button class="btn btn-primary" id="canv" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBoth" aria-controls="offcanvasBoth">Enable both scrolling &amp; backdrop</button>
          <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasBoth" aria-labelledby="offcanvasBothLabel">
            <div class="offcanvas-header">
              <h5 id="offcanvasBothLabel" class="offcanvas-title">Enable both scrolling &amp; backdrop</h5>
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body my-auto mx-0 flex-grow-0">
              <p class="text-center">Lorem ipsum, or lipsum as it is sometimes known, is dummy text used in laying out print, graphic or web designs. The passage is attributed to an unknown typesetter in the 15th century who is thought to have scrambled parts of Cicero's De Finibus Bonorum et Malorum for use in a type specimen book.</p>
              <button type="button" class="btn btn-primary mb-2 d-grid w-100">Continue</button>
              <button type="button" class="btn btn-outline-secondary d-grid w-100" data-bs-dismiss="offcanvas">Cancel</button>
            </div>
          </div>
        </div>
      </div> --}}
@endsection
