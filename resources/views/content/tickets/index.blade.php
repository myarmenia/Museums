@extends('layouts/contentNavbarLayout')

@section('content')

<h4 class="py-3 mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('educational_programs_list')}}">Տոմսեր</a>
            </li>
        </ol>
    </nav>
</h4>
<div class="row ">
    <div class="col">
        <div class="card my-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="card-header">Ստանդարտ տոմս</h6>
                </div>
            </div>
            <div class="card-body">

                <form action="{{ $ticket_standart == null ? route('ticket_standart_store') : route('ticket_standart_update', $ticket_standart->id)}}" method="post">
                    <div class="mb-3 row">
                        <label for="standart_price" class="col-md-2 col-form-label">Գին <span class="required-field text-danger">*</span></label>

                        <div class="col-md-10">
                            <input class="form-control" placeholder="Գին" value="{{ old("standart_price") }}" id="standart_price" name="standart_price" />
                        </div>
                    </div>
                    @error("standart_price")
                        <div class="mb-3 row justify-content-end">
                            <div class="col-sm-10 text-danger fts-14">{{ $message }}</div>
                        </div>
                    @enderror

                    <div class="row justify-content-end">
                      <div class="col-sm-10">
                          <button type="submit" class="btn btn-primary">Պահպանել</button>

                      </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card my-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="card-header">Աբոնիմենտ</h6>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ $ticket_subscription == null ? route('ticket_subscription_store') : route('ticket_subscription_update', $ticket_subscription->id)}}" method="post">
                    <div class="mb-3 row">
                        <label for="subscription_price" class="col-md-2 col-form-label">Գին <span class="required-field text-danger">*</span></label>

                        <div class="col-md-10">
                            <input class="form-control" placeholder="Գին" value="{{ old("subscription_price") }}" id="subscription_price" name="subscription_price" />
                        </div>
                    </div>
                    @error("subscription_price")
                        <div class="mb-3 row justify-content-end">
                            <div class="col-sm-10 text-danger fts-14">{{ $message }}</div>
                        </div>
                    @enderror

                    <div class="row justify-content-end">
                      <div class="col-sm-10">
                          <button type="submit" class="btn btn-primary">Պահպանել</button>

                      </div>
                  </div>

                </form>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card my-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="card-header">Էքսկուրսավար</h6>
                </div>
            </div>
            <div class="card-body">

                <form action="" method="post">

                    <div class="mb-3 row">
                        <label for="price_am" class="col-md-2 col-form-label">Գին <span class="required-field text-danger">*</span></label>

                        <div class="col-md-10">
                            <input class="form-control" placeholder="Գինը հայերեն լեզվի համար" value="{{ old("price_am") }}" id="price_am" name="price_am" />
                        </div>
                    </div>
                    @error("price_am")
                        <div class="mb-3 row justify-content-end">
                            <div class="col-sm-10 text-danger fts-14">{{ $message }}</div>
                        </div>
                    @enderror

                    <div class="mb-3 row">
                        <label for="price_other" class="col-md-2 col-form-label">Գին<span class="required-field text-danger">*</span></label>

                        <div class="col-md-10">
                            <input class="form-control" placeholder="Գինը օտար լեզվի համար" value="{{ old("price_other") }}" id="price_other" name="price_other" />
                        </div>
                    </div>
                    @error("price_other")
                        <div class="mb-3 row justify-content-end">
                            <div class="col-sm-10 text-danger fts-14">{{ $message }} </div>
                        </div>
                    @enderror



                    <div class="row justify-content-end">
                      <div class="col-sm-10">
                          <button type="submit" class="btn btn-primary">Պահպանել</button>

                      </div>
                  </div>

                </form>
            </div>
        </div>
    </div>

</div>

@endsection
