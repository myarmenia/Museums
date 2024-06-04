@extends('layouts/contentNavbarLayout')
@section('title', 'Հաշվետվություն')

 @section('title', 'Account settings - Account')
@section('page-script')
    <script src="{{ asset('assets/js/change-status.js') }}"></script>
    <script src="{{ asset('assets/js/delete-item.js') }}"></script>
    <script src="{{ asset('assets/js/admin/report/script.js') }}"></script>

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
                    <a href="javascript:void(0);">Հաշվետվություն 88888</a>
                </li>
            </ol>
        </nav>
    </h4>
    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-header">Հաշվետվություն</h5>
            </div>

        </div>
        <div class="card-body">

            <div>
                <form action="{{route('reports', 'report')}}" method="get" class="row g-3 mt-2" style="display: flex" id="form">
                    <div class="mb-3 justify-content-end" style="display: flex; gap: 8px">
                       <div class="col-2">
                            <select class="form-select select-2 multiselect selectdate" id="multiple-select-museum" data-placeholder="Թանգարան" name="museum_id[]" multiple>
                                  <option value="all" id="all_museums" {{ in_array('all', (array)request()->input('museum_id')) || count((array)request()->input('museum_id')) == 0 ? 'selected' : '' }}>Բոլորը</option>
                                  @foreach ($museums as $k => $museum)
                                      <option  value="{{$museum->id}}" {{ in_array($museum->id, (array)request()->input('museum_id')) ? 'selected' : '' }}>{{$museum->translationsForAdmin->name}}</option>
                                  @endforeach

                            </select>
                        </div>
                        <div class="col-2">
                            <select id="multiple-select-report_type" name="report_type" class="form-select select-2" data-placeholder="Հաշվետվության տեսակ" value="{{ request()->input('report_type') ?? ''}}" >
                                <option disabled >Հաշվետվության տեսակ</option>
                                <option value="financial" {{ request()->input('report_type') == 'financial' ? 'selected' : '' }}>Ֆինասական</option>
                                <option value="quantitative" {{ request()->input('report_type') == 'quantitative' ? 'selected' : '' }}>Քանակական</option>
                                <option value="fin_quant" {{ request()->input('report_type') == null || request()->input('report_type') == 'fin_quant' ? 'selected' : '' }}>Ֆին./Քանակ</option>
                            </select>
                        </div>

                        <div class="col-2">
                            <select id="multiple-select-type" name="type" class="form-select select-2" data-placeholder="Վճարման եղանակ" value="{{ request()->input('type') ?? ''}}" >
                                <option disabled selected>Վճարման եղանակ</option>
                                <option value="null" >Բոլորը</option>
                                <option value="online" {{ request()->input('type') == 'online' ? 'selected' : '' }}>Առցանց</option>
                                <option value="offline" {{ request()->input('type') == 'offline' ? 'selected' : '' }}>Դրամարկղ</option>

                            </select>
                        </div>

                        <div class="col-1">
                            <select id="multiple-select-gender" name="gender" class="form-select select-2" data-placeholder="Սեռ" value="{{ request()->input('gender') ?? ''}}" >
                                <option disabled selected>Սեռ</option>
                                <option value="null" >Բոլորը</option>
                                <option value="male" {{ request()->input('gender') == 'male' ? 'selected' : '' }}>Արական</option>
                                <option value="female" {{ request()->input('gender') == 'female' ? 'selected' : '' }}>Իգական</option>
                                <option value="other" {{ request()->input('gender') == 'other' ? 'selected' : '' }}>Այլ</option>
                                <option value="unknown" {{ request()->input('gender') == 'unknown' ? 'selected' : '' }}>Անհայտ</option>
                            </select>
                        </div>

                        <div class="col-2">
                            <select id="multiple-select-age" name="age" class="form-select select-2" data-placeholder="Տարիք" value="{{ request()->input('age') ?? ''}}" >
                                <option disabled selected>Տարիք</option>
                                <option value="null" >Բոլորը</option>
                                <option value="junior" {{ request()->input('age') == 'junior' ? 'selected' : '' }}>մինչև 18</option>
                                <option value="young" {{ request()->input('age') == 'young' ? 'selected' : '' }}>19 - 60</option>
                                <option value="old" {{ request()->input('age') == 'old' ? 'selected' : '' }}>61 և ավել</option>

                            </select>
                        </div>

                        <div class="col-2">
                            <select id="multiple-select-country" name="country_id" class="form-select select-2" data-placeholder="Երկիր" value="{{ request()->input('country_id') ?? ''}}" >
                                <option disabled selected>Երկիր</option>
                                <option value="null" >Բոլորը</option>
                                @foreach (getAllCountries() as $country)
                                    <option value="{{$country->id}}" {{ request()->input('country_id') == $country->id ? 'selected' : '' }}>{{$country->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 justify-content-end" style="display: flex; gap: 8px">

                        <div class="col-2">
                            <input type="date" title="Սկիզբ" class="form-control selectdate" id="datefrom" placeholder="Ստեղծման ամսաթիվ" name="from_created_at" value="{{ request()->input('from_created_at') }}" max="{{date('Y-m-d')}}">
                        </div>

                        <div class="col-2">

                            <input type="date" title="Ավարտ" class="form-control selectdate" id="dateto" placeholder="Ստեղծման ամսաթիվ" name="to_created_at" value="{{ request()->input('to_created_at') }}" max="{{date('Y-m-d')}}">
                        </div>

                        <div class="col-2">
                            <select id="multiple-select-time" name="time[]" class="form-select select-2 multiselect selectdate" data-placeholder="Ժամանակահատված" multiple>

                              @foreach (getReportTimesForAdmin() as $t => $time)
                                    <option value="{{$t}}" {{ in_array($t, (array)request()->input('time')) ? 'selected' :
                                          (count((array)request()->input('time')) == 0 &&
                                          request()->input('from_created_at') == null && request()->input('to_created_at') == null && $t == 'per_year' ? 'selected' : '') }}>{{ $time }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 justify-content-end" style="display: flex; gap: 8px">
                        <button class="btn btn-primary col-2 search">Հաշվետվություն</button>
                        <button class="btn btn-primary col-1 compare" disabled>Համեմատել</button>
                        <a class="btn btn-primary col-2 download_csv" href="{{ route('export_report_excel',  ['request_report_type' => request()->request_report_type == 'compare' ? 'compare' : 'report',
                                  'report_type' => request()->input('report_type'),
                                  'data' => $data, 'role_group' => 'admin'])}}" {{ count($data) == 0 ? 'disabled' : ''}}>Արտահանել XLSX</a>

                        <a class="btn btn-primary" href="{{ route('reports', 'report') }}">Չեղարկել</a>
                    </div>
                </form>
            </div>

            <x-report-result :data="$data"></x-report-result>

        </div>
    </div>

</section>

@endsection
