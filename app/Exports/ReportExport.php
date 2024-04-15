<?php

namespace App\Exports;

use App\Models\PurchasedItem;
use App\Traits\Reports\CheckReportType;
use App\Traits\Reports\ReportTrait;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReportExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */

  protected $data;

  public function __construct(array $data)
  {

    $this->data = $data;
  }

  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    // dd($this->data);
    // return collect($this->data);
    return collect($this->data);

  }

  public function headings(): array
  {
        return [
              'Թանգարան',
              'Ստանդարտ տ․',
              'Զեղչված տ․',
              'Անվճար տ․',
              'Միասնական տ․',
              'Աբոնեմենտ',
              'Միջոցառման տ․',
              'Կորպորատիվ',
              'Կրթական ծրագիր',
              'Էքսկուրսիա',
              'Ապրանքներ'
        ];
  }

  public function map($data): array
  {

        if (request()->routeIs('reports')){
            $name = isset($data['museum_id']) ? getMuseum($data['museum_id'])->translationsForAdmin->name : ' - ';
            $report['name'] = $name;
        }
        foreach (reportTypes() as $type){
          $k =  !empty($data[$type]) ? $data[$type]['total_price'] .' / '. $data[$type]['quantity'] : ' - ';
      $report[$type]=$k;
        }
    return $report;
  }
    // public function collection($data)
    // {

    //     // $data = $this->report($data, $this->model, $request_report_type);
    //     return $data;
    // }
}
