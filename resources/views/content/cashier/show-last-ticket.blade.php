@extends('layouts/contentNavbarLayout')

@section('title', 'Ապրանքների - Վերջին տոմս')

@section('content')
@include('includes.alert')
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
</div>


@endsection