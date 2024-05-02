@extends('layouts/contentNavbarLayout')

@section('title', 'Տոմսի վերադարձ')
@section('page-script')
    <script src="{{ asset('assets/js/return-ticket.js') }}"></script>
@endsection

@section('content')
    @include('includes.alert')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Տոմսի հետ վերադարձ</span>
    </h4>
    <div class="card">
        <div class="card-body">
            <div class="d-flex">
                <input type="text" id="unique_id" class="form-control" name="unique_id" placeholder="Ներմուծեք տոմսի թոքենը">
                <button class="ms-2 btn btn-primary col-2 search" id='send_unique_id'>Ստուգել</button>
            </div>

            <div id='returned_info' class="mt-2 row">

            </div>
        </div>
    </div>

@endsection
<x-modal-delete></x-modal-delete>
