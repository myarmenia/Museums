@extends('layouts/contentNavbarLayout')

@section('title', 'Դրամարկղ - Վերջին տոմս')

@section('content')
@include('includes.alert')

@if (session()->has('result_hdm'))

    <div class="session-message alert {{ session()->get('result_hdm')['success'] ? 'alert-success' : 'alert-danger' }} "> {{ session()->get('result_hdm')['success'] ? 'Կտրոնը տված է' : 'ՀԴՄ սարքի խնդիր' }}</div>
@endif

<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Դրամարկղ /</span> Վերջին տոմս
</h4>

<div class="card">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h5 class="card-header">Վերջին տոմս</h5>
        </div>
    </div>

    @if ($data)
        <div class="px-4 d-flex align-items-center">
            <div>
                <h6 >{{ $data->created_at }} - </h6>
            </div>
            <div>
                <h6 ><a href="{{ route('get-file',['path'=>$data->pdf_path]) }}" target="_blank"> Տեսնել</a></h6 >
            </div>
        </div>
    @else
        <h3 class="mx-4">Տոմս առկա չէ</h3>
    @endif
</div>

{{-- ================ For those museums that have a hdm =============================== --}}
 @if (museumHasHdm())
    <div class="card mt-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-header">ՀԴՄ-ի կրկնօրինակի տպում</h5>
            </div>
        </div>

        @if ($last_hdm)

            <div class="px-4 d-flex align-items-center">
                <div>
                    <h6 >{{ $last_hdm->created_at }} - </h6>
                </div>
                <div>
                    <h6 ><a href="{{ route('cashier.print_last_receipt_hdm') }}" > Տպել</a></h6 >
                </div>
            </div>
        @else
            <h3 class="mx-4">ՀԴՄ կտրոնը առկա չէ</h3>
        @endif
    </div>
{{-- </div> --}}
@endif

@endsection
