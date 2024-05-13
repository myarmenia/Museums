<div class="table-responsive text-nowrap">
  <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                @if (request()->routeIs('reports'))
                  <th>Թանգարան</th>
                @endif
                <th>Ստանդարտ տ․</th>
                <th>Զեղչված տ․</th>
                <th>Անվճար տ․</th>
                <th>Միասնական տ․</th>
                <th>Աբոնեմենտ</th>
                <th>Միջոցառման տ․</th>
                <th>Կորպորատիվ</th>
                <th>Կրթական ծրագիր</th>
                <th>Էքսկուրսիա</th>
                <th>Չեղարկված</th>
                <th>Ապրանքներ</th>
                @if (request()->request_report_type == 'compare')
                  <th>Ամսաթիվ</th>
                @endif
            </tr>
        </thead>
        @php
            $total_info = null;

            $sums = reportResult($data);

            $total_sums = array_sum(array_column($sums,'total_price'));
            $total_quantity = array_sum(array_column($sums,'quantity'));
        @endphp

        <tbody>
            @php $i = 0; $all_report_types = reportTypes(); @endphp
            @foreach ($data as $museum_id => $report)

              @if (request()->input('report_type') == 'fin_quant' || request()->input('report_type') == null)
                  <tr>
                      <td>{{ ++$i }}</td>
                      @if (request()->routeIs('reports'))
                          <td>{{ isset($report['museum_id']) ? getMuseum($report['museum_id'])->translationsForAdmin->name : ' - '}}</td>
                      @endif

                      @foreach ($all_report_types as $type)
                          <td>{{ !empty($report[$type]) ? $report[$type]['total_price'] .' / '. $report[$type]['quantity'] : ' - ' }}</td>
                      @endforeach
                      {{-- <td>{{ !empty($report['discount']) ? $report['discount']['total_price'] .' / '. $report['discount']['quantity'] : ' - '  }}</td>
                      <td>{{ !empty($report['free']) ? $report['free']['total_price'] .' / '. $report['free']['quantity'] : ' - '  }}</td>
                      <td>{{ !empty($report['united']) ? $report['united']['total_price'] .' / '. $report['united']['quantity'] : ' - ' }}</td>
                      <td>{{ !empty($report['subscription']) ? $report['subscription']['total_price'] .' / '. $report['subscription']['quantity'] : ' - '}}</td>
                      <td>{{ !empty($report['event']) ? $report['event']['total_price'] .' / '. $report['event']['quantity'] : ' - '}}</td>
                      <td>{{ !empty($report['corporative']) ? $report['corporative']['total_price'] .' / '. $report['corporative']['quantity'] : ' - '}}</td>
                      <td>{{ !empty($report['educational']) ? $report['educational']['total_price'] .' / '. $report['educational']['quantity'] : ' - '}}</td>
                      <td>{{ !empty($report['guide']) ? $report['guide']['total_price'] .' / '. $report['guide']['quantity'] : ' - '}}</td> --}}
                      @if (request()->request_report_type == 'compare')
                        <td>{{ !empty($report['start_date']) ? date('d.m.Y', strtotime($report['start_date'])) .' - '. date('d.m.Y', strtotime($report['end_date'])) : ' - '}}</td>
                      @endif
                  </tr>
              @elseif(request()->input('report_type') == 'financial')
                  <tr>
                      <td>{{ ++$i }}</td>
                      @if (request()->routeIs('reports'))
                          <td>{{ isset($report['museum_id']) ? getMuseum($report['museum_id'])->translationsForAdmin->name : ' - '}}</td>
                      @endif

                      @foreach ($all_report_types as $type)
                          <td>{{ !empty($report[$type]) ? $report[$type]['total_price'] : ' - ' }}</td>
                      @endforeach

                      @if (request()->request_report_type == 'compare')
                        <td>{{ !empty($report['start_date']) ? date('d.m.Y', strtotime($report['start_date'])) .' - '. date('d.m.Y', strtotime($report['end_date'])) : ' - '}}</td>
                      @endif
                  </tr>
              @else
                  <tr>
                      <td>{{ ++$i }}</td>
                      @if (request()->routeIs('reports'))
                          <td>{{ isset($report['museum_id']) ? getMuseum($report['museum_id'])->translationsForAdmin->name : ' - '}}</td>
                      @endif
                      @foreach ($all_report_types as $type)
                          <td>{{ !empty($report[$type]) ? $report[$type]['quantity'] : ' - ' }}</td>

                      @endforeach

                      @if (request()->request_report_type == 'compare')
                        <td>{{ !empty($report['start_date']) ? date('d.m.Y', strtotime($report['start_date'])) .' - '. date('d.m.Y', strtotime($report['end_date'])) : ' - '}}</td>
                      @endif
                  </tr>
            @endif

            @endforeach


            {{-- ============================================================== --}}
            @if (request()->request_report_type != 'compare' && count($data) > 0)
                @if (request()->input('report_type') == 'fin_quant' || request()->input('report_type') == null)
                        @php $total_info = $total_sums . ' / ' . $total_quantity;  @endphp
                @elseif(request()->input('report_type') == 'financial')
                          @php $total_info = $total_sums;   @endphp
                @else
                          @php  $total_info = $total_quantity;  @endphp
                @endif

                @if (count($data) > 1)
                  @role('super_admin|general_manager|chief_accountant')

                      @if (request()->input('report_type') == 'fin_quant' || request()->input('report_type') == null)

                        <tr class="table-primary">
                              <td>Ընդամենը</td>
                              <td>  - - -  </td>
                              @foreach ($all_report_types as $type)
                                  <td>{{ !empty($sums[$type]) ? $sums[$type]['total_price'] .' / '. $sums[$type]['quantity'] : ' - ' }}</td>

                              @endforeach

                          </tr>
                      @elseif(request()->input('report_type') == 'financial')

                          <tr class="table-primary">
                              <td>Ընդամենը</td>
                              <td>  - - -  </td>
                              @foreach ($all_report_types as $type)
                                  <td>{{ !empty($sums[$type]) ? $sums[$type]['total_price'] : ' - ' }}</td>

                              @endforeach

                          </tr>
                      @else
                          
                          <tr class="table-primary">
                              <td>Ընդամենը</td>
                              <td>  - - -  </td>
                              @foreach ($all_report_types as $type)
                                  <td>{{ !empty($sums[$type]) ? $sums[$type]['quantity'] : ' - ' }}</td>

                              @endforeach

                          </tr>
                      @endif
                  @endrole
              @endif

            @endif
        </tbody>
  </table>
</div>

@if ($total_info != null)
  <div class="d-flex justify-content-end w-100 mt-4">
      <div>Ընդամենը` {{$total_info}}</div>
  </div>
@endif

