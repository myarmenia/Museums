<?php
namespace App\Traits\Reports;

trait CheckReportType
{

  public function report($data, $model, $request_report_type)
  {



    $get_report_times = getReportTimes();
    if(isset($data['museum_id']) && in_array('all', $data['museum_id'])){

      unset($data['museum_id']);
    }

    if (isset($data['time']) && count($data['time']) == 1) {
      // $get_report_times = getReportTimes();

      $data['start_date'] = $get_report_times[$data['time'][0]]['start_date'];
      $data['end_date'] = $get_report_times[$data['time'][0]]['end_date'];
    }

    if ($request_report_type == 'compare') {
      if (isset($data['time']) && count($data['time']) > 1) {

        $result = [];

        foreach ($data['time'] as $d => $time) {

          $data['start_date'] = $get_report_times[$time]['start_date'];
          $data['end_date'] = $get_report_times[$time]['end_date'];

          $compare_data = $this->generateReport($data, $model);

          if(request()->routeIs('museum_reports')){
                $compare_data_key = array_key_first($compare_data);
                if (isset($compare_data[$compare_data_key]['educational']['quantity']) && request()->routeIs('museum_reports')) {   /// for educational tatal count via rows in table
                  $educational_total_count = $this->totalEducational($data, $this->model);

                  $compare_data[$compare_data_key]['educational']['total_count'] = $educational_total_count;
                }
          }

          $compare_data = array_values(array_filter(array_values($compare_data)));

          $compare_data[0]['start_date'] = $get_report_times[$time]['start_date'];
          $compare_data[0]['end_date'] = $get_report_times[$time]['end_date'];

          array_push($result, $compare_data);

        }

        $result = array_values(array_reduce($result, 'array_merge', []));


      } else {

        $result = $this->generateReport($data, $model);

      }
    } else {

      $result = $this->generateReport($data, $model);

      if(request()->routeIs('museum_reports')){

        $result_key = array_key_first($result);

        if (isset($result[$result_key]['educational']['quantity'])) {   /// for educational tatal count via rows in table
          $educational_total_count = $this->totalEducational($data, $this->model);
          $result[$result_key]['educational']['total_count'] = $educational_total_count;
        }

      }


    }

    return $result;

  }


}
