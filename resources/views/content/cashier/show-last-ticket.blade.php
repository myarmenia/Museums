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
        <div>
            <div>
                <button>Տեսնել</button>
            </div>
            <div>
                <p>{{ $data->created_at }}</p>
            </div>
        </div>
    @else
        <h3 class="mx-4">Տոմս առկա չէ</h3>
    @endif
</div>
</div>


@endsection