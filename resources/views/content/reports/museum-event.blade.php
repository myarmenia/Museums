@extends('layouts/contentNavbarLayout')
@section('title', 'Հաշվետվություն - Ցանկ')

 @section('title', 'Account settings - Account')
@section('page-script')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />

    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
    <script>
          $( '.select-2').select2( {
              theme: "bootstrap-5",
              width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
              placeholder: $( this ).data( 'placeholder' ),
              closeOnSelect: false,
          } );
    </script>

@endsection

@section('content')

    <h4 class="py-3 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);">Միջոցառման հաշվետվություն</a>
                </li>
            </ol>
        </nav>
    </h4>
    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-header">Միջոցառման հաշվետվություն</h5>
            </div>

        </div>
        <div class="card-body">

            <div>
                <form action="{{route('museum_event_reports')}}" method="get" class="row g-3 mt-2" style="display: flex" id="form">
                    <div class="mb-3 d-flex justify-content-end" >

                        <div class="col-2">
                            <select id="multiple-select-event" name="item_relation_id" class="form-select select-2" data-placeholder="Միջոցառում" value="{{ request()->input('item_relation_id') ?? ''}}" >
                                <option disabled selected>Միջոցառում</option>
                                @foreach (getMuseumAllEvents(museumAccessId()) as $event)
                                    <option value="{{$event->id}}" {{ request()->input('item_relation_id') == $event->id ? 'selected' : '' }}>{{$event->translation('am')->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-2">
                            <input type="date" title="Սկիզբ" class="form-control selectdate" id="datefrom" placeholder="Ստեղծման ամսաթիվ" name="from_created_at" value="{{ request()->input('from_created_at') }}" max="{{date('Y-m-d')}}">
                        </div>

                        <div class="col-2">

                            <input type="date" title="Ավարտ" class="form-control selectdate" id="dateto" placeholder="Ստեղծման ամսաթիվ" name="to_created_at" value="{{ request()->input('to_created_at') }}" max="{{date('Y-m-d')}}">
                        </div>
                        <div class="col-4 justify-content-end" style="display: flex; gap: 8px">
                            <button class="btn btn-primary search">Հաշվետվություն</button>
                            <a class="btn btn-primary" href="{{ route('museum_event_reports') }}">Չեղարկել</a>
                        </div>

                    </div>

                </form>
            </div>

            @if (count($data) > 0)
            {{-- {{dd($data)}} --}}
                <x-event-report-result :data="$data"></x-event-report-result>
            @endif

        </div>
    </div>

</section>

@endsection
