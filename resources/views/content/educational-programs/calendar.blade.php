@extends('layouts/contentNavbarLayout')
@section('page-script')
{{-- <script>
    let student_id = {{ $student->id }}
</script> --}}

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script src="{{ asset('assets/js/admin/calendar.js') }}"></script>
{{-- <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'> --}}
<link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/bootstrap5@6.1.10/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.11/locales-all.global.min.js'></script>
<link href="{{ asset('assets/css/admin/calendar.css') }}" rel='stylesheet' />


@endsection
@section('content')

<h4 class="py-3 mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('educational_programs_list')}}">Կրթական ծրագրեր</a>
            </li>
            <li class="breadcrumb-item active">Ստեղծել նոր ծրագիր</li>

        </ol>
    </nav>
</h4>

<div class="card">

    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="kt_docs_fullcalendar_locales">Ստեղծել նոր ծրագիր</h4>
        </div>

    </div>
    <div class="card-body">
        <div id='calendar'></div>
        <form action="{{route('educational_programs_store')}}" method="post">

        </form>


    </div>


</div>


@endsection
