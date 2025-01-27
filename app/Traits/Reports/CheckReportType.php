<?php
namespace App\Traits\Reports;

trait CheckReportType
{

  public function report($data, $model, $request_report_type)
  {

    // dd($data['time']);

    $get_report_times = getReportTimes();
    if(isset($data['museum_id']) && in_array('all', $data['museum_id'])){

      unset($data['museum_id']);
    }

    if (isset($data['time']) && count($data['time']) == 1) {
      // $get_report_times = getReportTimes();

      $data['start_date'] = $get_report_times[$data['time'][0]]['start_date'];
      $data['end_date'] = $get_report_times[$data['time'][0]]['end_date'];
    }
    // dd($data['time']);
    if ($request_report_type == 'compare') {
      if (isset($data['time']) && count($data['time']) > 1) {
        // $get_report_times = getReportTimes();
        $result = [];

        foreach ($data['time'] as $d => $time) {

          $data['start_date'] = $get_report_times[$time]['start_date'];
          $data['end_date'] = $get_report_times[$time]['end_date'];

          $compare_data = $this->generateReport($data, $model);

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
    }

dd($result);
    $result = $this->educationalTotalCount($result, $data, $model);


    return $result;

  }


  public function educationalTotalCount($result, $data, $model){
    $data['type'] = 'educational';


    $data = $this->generateReport($data, $model);

    dd($data);
  }

}
