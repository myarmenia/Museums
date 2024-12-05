<div class="table-responsive text-nowrap">
  <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                @if (!isset(request()->partner_id))
                  <th>Գործընկեր</th>
                @endif
                <th>Ստանդարտ տ․ </th>
                <th>Զեղչված տ․</th>
                <th>Անվճար տ․</th>
                <th>Չեղարկված</th>
                <th>Էքսկուրսավար (հայ)</th>
                <th>Էքսկուրսավար (այլ)</th>
                @if (isset(request()->partner_id))
                  <th>Մեկնաբանություն</th>
                @endif
                <th>Ամսաթիվ</th>

            </tr>
        </thead>
        @php
              $total_info = null;

              $sums = reportResult($data);
              $newSums = array_diff_key($sums, ['canceled' => '']);

              $total_sums = array_sum(array_column($newSums,'total_price'));
              $total_quantity = array_sum(array_column($newSums,'quantity'));

        @endphp

        <tbody>

            @php $i = 0; $all_report_types = ['standart', 'discount','free', 'canceled','partner_guide_am', 'partner_guide_other']; @endphp
            @foreach ($data as $item_id => $report)

                  <tr>
                      <td>{{ $loop->iteration + (($data->currentPage() - 1) * $data->perPage()) }}</td>
                      @if (!isset(request()->partner_id))
                          <td>{{ isset($report['partner_id']) ? getPartner($report['partner_id'])->name : ' - '}}</td>
                      @endif

                      @foreach ($all_report_types as $type)
                          <td>{{ !empty($report[$type]) ? $report[$type]['total_price'] .' / '. $report[$type]['quantity'] : ' - ' }}</td>

                      @endforeach

                      @if (isset(request()->partner_id))
                            <td>{{ getPurchaseComment($item_id) ?? ' - - - ' }}</td>
                      @endif

                      @if (isset(request()->partner_id))
                            <td>{{ $report['date'] ?? ' - '}} </td>
                      @else
                            <td>{{ !empty(request()->input('from_created_at')) ? date('d.m.Y', strtotime(request()->input('from_created_at'))) : '' }}  -
                              {{!empty(request()->input('to_created_at')) ? date('d.m.Y', strtotime(request()->input('to_created_at'))) : ''}}</td>

                      @endif



                  </tr>


            @endforeach


            {{-- ============================================================== --}}


        </tbody>
  </table>
</div>

<div class="demo-inline-spacing">
    {{ $data->links() }}
</div>
@if ($total_info != null)
  <div class="d-flex justify-content-end w-100 mt-4">
      <div>Ընդամենը` {{$total_info}}</div>
  </div>
@endif

