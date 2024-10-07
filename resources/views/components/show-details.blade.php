<div class="modal fade" id="showDetails" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel2">Մանրամասը դիտում</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        @if (isset($details))

            @if ($details->getTable() == 'purchases')
                @php
                    $total_sums = 0;
                    $total_quantity =0;
                @endphp
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Տեսակ</th>
                                <th>Քանակ</th>
                                <th>Գին</th>
                            </tr>
                        </thead>
                        <tbody>
                          @foreach ($details->purchased_items as $key => $purchased_item)
                            @php
                              $total_sums += $purchased_item->total_price;
                              $total_quantity += $purchased_item->quantity;
                            @endphp
                          <tr>
                                <td>{{++$key}}</td>
                                <td>{{ __("ticket-type.$purchased_item->type")}}</td>
                                <td>{{$purchased_item->quantity}}</td>
                                <td>{{$purchased_item->total_price}}</td>
                            </tr>
                          @endforeach

                        </tbody>
                      </table>
                      <div class="modal-footer">
                        <p>Ընդամենը Գին/Քանակ - {{$total_sums}} / {{$total_quantity}}</p>
                      </div>
                  </div>
            @else
                  <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">

                        <tbody>
                            <tr>
                                <td>ID</td>
                                <td>{{$details->id}}</td>
                            </tr>
                            <tr>
                                <td>Թոքեն</td>
                                <td>{{$details->ticket_token}}</td>
                            </tr>
                            <tr>
                                <td>Տեսակ</td>
                                <td>{{ __("ticket-type.$details->type")}}</td>
                            </tr>
                            <tr>
                                <td>Գին</td>
                                <td>{{$details->price}}</td>
                            </tr>

                        </tbody>
                      </table>
                  </div>
            @endif
        @endif

      </div>
  
    </div>
  </div>
</div>
