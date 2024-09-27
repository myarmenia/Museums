<div class="table-responsive text-nowrap">
  <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                @if (!isset(request()->item_relation_id))
                  <th>Գործընկեր</th>
                @endif
                <th>Ստանդարտ տ․ </th>
                <th>Զեղչված տ․</th>
                <th>Անվճար տ․</th>
                <th>Չեղարկված</th>
                <th>partner_guide_am</th>
                <th>partner_guide_other</th>
                <th>Ամսաթիվ</th>
                {{-- @if (request()->request_report_type == 'compare')
                  <th>Ամսաթիվ</th>
                @endif --}}
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
            @php $i = 0; $all_report_types = ['standart', 'discount','free', 'returned','partner_guide_am', 'partner_guide_other']; @endphp
            @foreach ($data as $museum_id => $report)


                  <tr>
                      <td>{{ ++$i }}</td>
                      @if (!isset(request()->item_relation_id))
                          <td>{{ isset($report['museum_id']) ? getMuseum($report['museum_id'])->translationsForAdmin->name : ' - '}}</td>
                      @endif

                      @foreach ($all_report_types as $type)
                          <td>{{ !empty($report[$type]) ? $report[$type]['total_price'] .' / '. $report[$type]['quantity'] : ' - ' }}</td>
                      @endforeach


                      {{-- @if (request()->request_report_type == 'compare') --}}
                        <td>{{ !empty(request()->input('from_created_at')) ? date('d.m.Y', strtotime(request()->input('from_created_at'))) : '' }}  -
                        {{!empty(request()->input('to_created_at')) ? date('d.m.Y', strtotime(request()->input('to_created_at'))) : ''}}</td>
                      {{-- @endif --}}
                  </tr>


            @endforeach


            {{-- ============================================================== --}}


        </tbody>
  </table>
</div>

@if ($total_info != null)
  <div class="d-flex justify-content-end w-100 mt-4">
      <div>Ընդամենը` {{$total_info}}</div>
  </div>
@endif

