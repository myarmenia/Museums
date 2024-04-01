@extends('layouts/contentNavbarLayout')

 @section('title', 'Account settings - Account')
@section('page-script')


    <script src="{{ asset('assets/js/change-status.js') }}"></script>
    <script src="{{ asset('assets/js/delete-item.js') }}"></script>

<!-- Styles -->
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" /> --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<!-- Or for RTL support -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />

<!-- Scripts -->
{{-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
<script>
      $( '.select-2' ).select2( {
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
                <form action="{{route('logs')}}" method="get" class="row g-3 mt-2" style="display: flex">
                    <div class="mb-3 justify-content-end" style="display: flex; gap: 8px">


                        <div class="col-2">
                            {{-- <select id="defaultSelect" name="museum_id" class="form-select" value="{{ request()->input('type') }}" > --}}
                              <select class="form-select select-2" id="multiple-select-museum" data-placeholder="Թանգարան" multiple>
                                    {{-- <option value="" disabled selected>Թանգարան</option> --}}
                                    @foreach ($museums as $museum)
                                        <option value="{{$museum->id}}" {{ request()->input('museum_id') == $museum->id ? 'selected' : '' }}>{{$museum->translationsForAdmin->name}}</option>
                                    @endforeach

                            </select>
                        </div>
                        <div class="col-2">
                            <select id="multiple-select-type" name="type" class="form-select select-2" value="{{ request()->input('type') ?? ''}}" data-placeholder="Հաշվետվության տեսակ">
                                <option value="financial" >Ֆինասական</option>
                                <option value="quantitative" >Քանակական</option>
                                <option value="fin_quant" >Քանակ/Ֆին</option>
                            </select>
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
                            <th>Օգտագործող</th>
                            <th>Դեր</th>
                            <th>Գործ․ տեսակ</th>
                            <th>Գործ․ օբյեկտ</th>
                            <th>Տվյալներ</th>
                            <th>Ամսաթիվ</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($data as $key => $log) --}}

                            {{-- <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $log->user->name }} {{ $log->user->surname }}</td>
                                <td>{{ __("roles.".$log->user->roles[0]->name) }}</td>
                                <td>{{ __("logs.$log->type") }}</td>
                                <td>{{ __("db_table.$log->tb_name") }}</td>
                                <td>{{ $log->data }}</td>
                                <td>{{ $log->created_at->format('d-m-Y')}}</td>

                            </tr> --}}
                        {{-- @endforeach --}}
                    </tbody>
                </table>
            </div>
            <div class="demo-inline-spacing">
                {{-- {{ $data->links() }} --}}
            </div>
        </div>
    </div>





</section>

@endsection


{{-- <x-modal-delete></x-modal-delete> --}}
