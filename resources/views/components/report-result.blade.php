<table class="table table-bordered">
      <thead>
          <tr>
              <th>No</th>
              <th>Թանգարան</th>
              <th>Ստանդարտ տ․</th>
              <th>Զեղչված տ․</th>
              <th>Անվճար տ․</th>
              <th>Միասնական տ․</th>
              <th>Աբոնեմենտ</th>
              <th>Միջողառման տ․</th>
              <th>Կորպորատիվ</th>
              <th>Կրթական ծրագիր</th>
              <th>Էքսկուրսիա</th>
          </tr>
      </thead>
      <tbody>
        @php $i = 0 @endphp
          @foreach ($data as $museum_id => $report)
          @if (request()->input('report_type') == 'fin_quant' || request()->input('report_type') == null)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ getMuseum($museum_id)->translationsForAdmin->name }}</td>
                    <td>{{ !empty($report['standart']) ? $report['standart']['total_price'] .' / '. $report['standart']['quantity'] : ' - ' }}</td>
                    <td>{{ !empty($report['discount']) ? $report['discount']['total_price'] .' / '. $report['discount']['quantity'] : ' - '  }}</td>
                    <td>{{ !empty($report['free']) ? $report['free']['total_price'] .' / '. $report['free']['quantity'] : ' - '  }}</td>
                    <td>{{ !empty($report['united']) ? $report['united']['total_price'] .' / '. $report['united']['quantity'] : ' - ' }}</td>
                    <td>{{ !empty($report['subscription']) ? $report['subscription']['total_price'] .' / '. $report['subscription']['quantity'] : ' - '}}</td>
                    <td>{{ !empty($report['event']) ? $report['event']['total_price'] .' / '. $report['event']['quantity'] : ' - '}}</td>
                    <td>{{ !empty($report['corporative']) ? $report['corporative']['total_price'] .' / '. $report['corporative']['quantity'] : ' - '}}</td>
                    <td>{{ !empty($report['educational']) ? $report['educational']['total_price'] .' / '. $report['educational']['quantity'] : ' - '}}</td>
                    <td>{{ !empty($report['guide']) ? $report['guide']['total_price'] .' / '. $report['guide']['quantity'] : ' - '}}</td>

                </tr>
            @elseif(request()->input('report_type') == 'financial')
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ getMuseum($museum_id)->translationsForAdmin->name }}</td>
                    <td>{{ !empty($report['standart']) ? $report['standart']['total_price'] : ' - ' }}</td>
                    <td>{{ !empty($report['discount']) ? $report['discount']['total_price'] : ' - '  }}</td>
                    <td>{{ !empty($report['free']) ? $report['free']['total_price'] : ' - '  }}</td>
                    <td>{{ !empty($report['united']) ? $report['united']['total_price'] : ' - ' }}</td>
                    <td>{{ !empty($report['subscription']) ? $report['subscription']['total_price'] : ' - '}}</td>
                    <td>{{ !empty($report['event']) ? $report['event']['total_price'] : ' - '}}</td>
                    <td>{{ !empty($report['corporative']) ? $report['corporative']['total_price'] : ' - '}}</td>
                    <td>{{ !empty($report['educational']) ? $report['educational']['total_price'] : ' - '}}</td>
                    <td>{{ !empty($report['guide']) ? $report['guide']['total_price'] : ' - '}}</td>

                </tr>
            @else
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ getMuseum($museum_id)->translationsForAdmin->name }}</td>
                    <td>{{ !empty($report['standart']) ? $report['standart']['quantity'] : ' - ' }}</td>
                    <td>{{ !empty($report['discount']) ? $report['discount']['quantity'] : ' - '  }}</td>
                    <td>{{ !empty($report['free']) ? $report['free']['quantity'] : ' - '  }}</td>
                    <td>{{ !empty($report['united']) ? $report['united']['quantity'] : ' - ' }}</td>
                    <td>{{ !empty($report['subscription']) ? $report['subscription']['quantity'] : ' - '}}</td>
                    <td>{{ !empty($report['event']) ? $report['event']['quantity'] : ' - '}}</td>
                    <td>{{ !empty($report['corporative']) ? $report['corporative']['quantity'] : ' - '}}</td>
                    <td>{{ !empty($report['educational']) ? $report['educational']['quantity'] : ' - '}}</td>
                    <td>{{ !empty($report['guide']) ? $report['guide']['quantity'] : ' - '}}</td>

                </tr>
          @endif

          @endforeach
      </tbody>
</table>
