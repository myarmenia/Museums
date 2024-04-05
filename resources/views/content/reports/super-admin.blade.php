@extends('layouts/contentNavbarLayout')

 @section('title', 'Account settings - Account')
@section('page-script')
    <script src="{{ asset('assets/js/change-status.js') }}"></script>
    <script src="{{ asset('assets/js/delete-item.js') }}"></script>

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
                    <a href="javascript:void(0);">Լոգավորում</a>
                </li>
                <li class="breadcrumb-item active">Ցանկ</li>
            </ol>
        </nav>
    </h4>
    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-header">Գործողությունների ցանկ</h5>
            </div>

        </div>
        <div class="card-body">

            <div>
                <form action="{{route('reports')}}" method="get" class="row g-3 mt-2" style="display: flex">
                    <div class="mb-3 justify-content-end" style="display: flex; gap: 8px">
                       <div class="col-2">
                            {{-- <select id="defaultSelect" name="museum_id" class="form-select" value="{{ request()->input('type') }}" > --}}
                              <select class="form-select select-2" id="multiple-select-museum" data-placeholder="Թանգարան" name="museum_id[]" multiple>
                                    @foreach ($museums as $museum)
                                        <option value="{{$museum->id}}" {{ request()->input('museum_id') == $museum->id ? 'selected' : '' }}>{{$museum->translationsForAdmin->name}}</option>
                                    @endforeach

                            </select>
                        </div>
                        <div class="col-2">
                            <select id="multiple-select-type" name="type" class="form-select select-2" data-placeholder="Հաշվետվության տեսակ" value="{{ request()->input('type') ?? ''}}" >
                                <option disabled selected>Հաշվետվության տեսակ</option>
                                <option value="financial" >Ֆինասական</option>
                                <option value="quantitative" >Քանակական</option>
                                <option value="fin_quant" >Քանակ/Ֆին</option>
                            </select>
                        </div>

                        <div class="col-2">
                            <select id="multiple-select-payment_method" name="payment_method" class="form-select select-2" data-placeholder="Վճարման եղանակ" value="{{ request()->input('payment_method') ?? ''}}" >
                                <option disabled selected>Վճարման եղանակ</option>
                                <option value="online" >Առցանց</option>
                                <option value="cash_box" >Դրամարկղ</option>

                            </select>
                        </div>

                        <div class="col-1">
                            <select id="multiple-select-gender" name="gender" class="form-select select-2" data-placeholder="Սեռ" value="{{ request()->input('gender') ?? ''}}" >
                                <option disabled selected>Սեռ</option>
                                <option value="male" >Արական</option>
                                <option value="female" >Իգական</option>
                                <option value="unknown" >Անհայտ</option>
                            </select>
                        </div>

                        <div class="col-2">
                            <select id="multiple-select-age" name="age" class="form-select select-2" data-placeholder="Տարիք" value="{{ request()->input('age') ?? ''}}" >
                                <option disabled selected>Տարիք</option>
                                <option value="0-12" >մինչև 12</option>
                                <option value="13-18" >13 - 18</option>
                                <option value="19-60" >19 - 60</option>
                                <option value="61" >61 և ավել</option>

                            </select>
                        </div>

                        <div class="col-2">
                            <select id="multiple-select-country" name="country_id" class="form-select select-2" data-placeholder="Երկիր" value="{{ request()->input('country_id') ?? ''}}" >
                                <option disabled selected>Երկիր</option>
                                @foreach (getAllCountries() as $country)
                                    <option value="{{$country->id}}" {{ request()->input('country_id') == $country->id ? 'selected' : '' }}>{{$country->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 justify-content-end" style="display: flex; gap: 8px">

                        <div class="d-flex">
                            <input type="date" title="Սկիզբ" class="form-control" id="datefrom" placeholder="Ստեղծման ամսաթիվ" name="from_created_at" value="{{ request()->input('from_created_at') }}">
                        </div>

                        <div class="d-flex">

                            <input type="date" title="Ավարտ" class="form-control" id="dateto" placeholder="Ստեղծման ամսաթիվ" name="to_created_at" value="{{ request()->input('to_created_at') }}">
                        </div>

                        <div class="col-2">
                            <select id="multiple-select-time" name="time" class="form-select select-2" value="{{ request()->input('time') ?? ''}}" data-placeholder="Ժամանակահատված" multiple>
                                <option value="first_trimester" >1 եռամսյակ</option>
                                <option value="second_trimester" >2 եռամսյակ</option>
                                <option value="third_trimester" >3 եռամսյակ</option>
                                <option value="fourth_trimester" >4 եռամսյակ</option>
                                <option value="first_semester" >1 կիսամյակ</option>
                                <option value="second_semester">2 կիսամյակ</option>
                                <option value="per year" >տարեկան</option>

                            </select>
                        </div>



                        <button class="btn btn-primary col-1 search">Որոնել</button>
                        <button class="btn btn-primary col-1 compare" >Համեմատել</button>
                        <button class="btn btn-primary col-2 download_csv">Արտահանել CSV </button>
                        <a class="btn btn-primary" href="{{ route('logs') }}">Չեղարկել</a>
                    </div>
                </form>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Թանգարան</th>
                            <th>Ստանդարտ տ․</th>
                            <th>Զեղչված տ․</th>
                            <th>Անվճար տ․</th>
                            <th>Միասնական տ․</th>
                            <th>Աբոնեմենտ</th>
                            <th>Միջողառման տ․</th>
                            <th>Կորպորատիվ</th>
                            <th>Կրթական ծրագիր</th>
                            <th>Էքսկուրսիա</th>
                            <th>Ամսաթիվ</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($data as $key => $report)

                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $report->museum->translationsForAdmin->name }}</td>
                                <td>{{ __("roles.".$log->user->roles[0]->name) }}</td>
                                <td>{{ __("logs.$log->type") }}</td>
                                <td>{{ __("db_table.$log->tb_name") }}</td>
                                <td>{{ $log->data }}</td>
                                <td>{{ $log->created_at->format('d-m-Y')}}</td>

                            </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>

        </div>
    </div>





</section>

@endsection
