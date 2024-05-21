@extends('layouts/contentNavbarLayout')

@section('title', 'Բարի գալուստ')

@section('content')

    <div class="row">
        <div class="d-flex align-items-center flex-column">
          <div class="card-body d-flex justify-content-center align-items-center">
            <div class="" style="width: 60%; text-align: center">
              <h2 class="card-header">Հայաստանի Հանրապետության թանգարանների միասնական տոմսային հարթակ</h2>
            </div>
          </div>
          <div class="pt-3">
            <h3 class="card-header">Բարի գալուստ {{auth()->user()->name . ' ' . auth()->user()->surname}}</h3>
          </div>
        </div>
    </div>
@endsection