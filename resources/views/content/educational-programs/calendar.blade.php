@extends('layouts/contentNavbarLayout')
@section('title', 'Այցելությունների օրացույց - Ցանկ')
@section('page-script')


    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <script src="{{ asset('assets/js/admin/calendar.js') }}"></script>
    {{-- <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'> --}}
    <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/bootstrap5@6.1.10/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.11/locales-all.global.min.js'></script>
    <link href="{{ asset('assets/css/admin/calendar.css') }}" rel='stylesheet' />
@endsection
@section('content')
    @if (!museumAccessId())
        <div class="alert alert-danger"> Նախ ստեղծեք թանգարան </div>
    @endif
    <h4 class="py-3 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('educational_programs_list') }}">Այցելությունների օրացույց և ամրագրում</a>
                </li>
            </ol>
        </nav>
    </h4>

<div class="container">
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-12">
            <div class="card mb-4">
                <div class="card-body py-0">
                    <div id='calendar'></div>

                </div>
            </div>

        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Նոր ամրագրում</h5>
                </div>
                <div class="card-body">
                    <form class="reserve" method="post">
                        <div class="mb-3">
                            <label class="form-label" for="educational_program">Ծրագրի տեսակը <span class="required-field text-danger">*</span></label>
                            <select id="educational_program" name="educational_program_id" class="form-select item educational_program_id">
                                <option value="" disabled selected>Ծրագրի տեսակը</option>

                                @foreach (museumEducationalPrograms() as $item)
                                    @if ($item->status)
                                        <option value="{{ $item->id }}">{{$item->translation('am')->name}}</option>
                                    @endif
                                @endforeach
                                <option value="null_id">Էքսկուրսիա</option>

                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="date">Այցելության օրը <span class="required-field text-danger">*</span></label>
                            <input type="date" class="form-control item" id="date" placeholder="Այցելության օրը"
                                name="date" min="{{date('Y-m-d')}}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="time">Այցելության ժամը <span class="required-field text-danger">*</span></label>
                            <input type="time" class="form-control item" id="time" placeholder="Այցելության ժամը"
                                name="time" min="00:00" max="23:59" step="60">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="visitor_quantity">Այցելուների քանակը <span class="required-field text-danger">*</span></label>
                            <input type="text" class="form-control item" id="visitor_quantity"
                                placeholder="Այցելության քանակը" name="visitor_quantity">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="description">Մանրամասներ <span class="required-field text-danger">*</span></label>
                            <textarea id="description" class="form-control item" placeholder="Մանրամասներ" name="description"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary ">Ամրագրել</button>
                        <div class="result_message mt-2"></div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="your-component">

    <x-offcanvas :reservetions=" isset($reservetions) ? $reservetions : [] " ></x-offcanvas>
</div>

@endsection
