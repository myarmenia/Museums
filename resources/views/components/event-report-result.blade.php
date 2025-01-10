@php

    $item = $data['item'];
    $data = $data['data'] ? $data['data'] : [];

    $total_quantity = array_reduce($data, function ($carry, $item) {
        return $carry + (isset($item['quantity']) ? (int)$item['quantity'] : 0);
    }, 0);

    $total_price = array_reduce($data, function ($carry, $item) {
        return $carry + (isset($item['total_price']) ? (int)$item['total_price'] : 0);
    }, 0);

@endphp
<div class="row mb-12 g-6">

    <div class="card pt-2">
      <img class="card-img-top img-thumbnail" style="width:200px; height:auto" src="{{ route('get-file', ['path' => $item->images[0]->path]) }}" alt="Card image cap">
      <div class="card-body">
        <h5 class="card-title">{{$item->style == 'basic' ? 'Միջոցառում' : 'Ցուցադրություն'}} - {{$item->translation('am')->name}}</h5>

      </div>
      <ul class="list-group list-group-flush">
        <li class="list-group-item"><h5>Ստանդարտ </h5></li>
        <li class="list-group-item">Ընդամենը քանակ - {{$data['standart']['quantity'] ?? 0}} </li>
        <li class="list-group-item">Ընդամենը գին - {{$data['standart']['total_price'] ?? 0}}</li>
      </ul>
      <ul class="list-group list-group-flush mt-2">
        <li class="list-group-item"><h5>Զեղչված </h5></li>
        <li class="list-group-item">Ընդամենը քանակ - {{$data['discount']['quantity'] ?? 0}} </li>
        <li class="list-group-item">Ընդամենը - {{$data['discount']['total_price'] ?? 0}} դրամ</li>
      </ul>
      <ul class="list-group list-group-flush mt-2">
        <li class="list-group-item"><h5>Անվճար </h5></li>
        <li class="list-group-item">Ընդամենը քանակ - {{$data['free']['quantity'] ?? 0}} </li>
        <li class="list-group-item">Ընդամենը - {{$data['free']['total_price'] ?? 0}} դրամ</li>
      </ul>
      <ul class="list-group list-group-flush mt-2">
        <li class="list-group-item"><h5> Էքսկուրսիա հայերեն</h5></li>
        <li class="list-group-item">Ընդամենը քանակ - {{$data['guide_price_am']['quantity'] ?? 0}} </li>
        <li class="list-group-item">Ընդամենը - {{$data['guide_price_am']['total_price'] ?? 0}} դրամ</li>
      </ul>
      <ul class="list-group list-group-flush mt-2">
        <li class="list-group-item"><h5> Էքսկուրսիա օտար լեզու</h5></li>
        <li class="list-group-item">Ընդամենը քանակ - {{$data['guide_price_other']['quantity'] ?? 0}} </li>
        <li class="list-group-item">Ընդամենը - {{$data['guide_price_other']['total_price'] ?? 0}} դրամ</li>
      </ul>
      <ul class="list-group list-group-flush mt-2">
        <li class="list-group-item"><h5> Գործընկեր</h5></li>
        <li class="list-group-item">Ընդամենը քանակ - {{$data['partner']['quantity'] ?? 0}} </li>
        <li class="list-group-item">Ընդամենը - {{$data['partner']['total_price'] ?? 0}} դրամ</li>
      </ul>
      <div class="card-body">
        <h4 class="card-link">Ընդամենը -  {{$total_price}} դրամ</h4>
        <h4  class="card-link">Ընդամենը քանակ - {{$total_quantity}}</h4>

      </div>
    </div>
</div>
