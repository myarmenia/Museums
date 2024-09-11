<?php

namespace App\Exports;

use App\Models\PurchasedItem;
use App\Traits\Reports\CheckReportType;
use App\Traits\Reports\ReportTrait;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
  /**
   * @return \Illuminate\Support\Collection
   */

  protected $data;
  protected $request;
  protected $report_type;
  protected $request_report_type;
  protected $role_group;
  private $rowNumber = 0;

  public function __construct($request)
  {

    $this->request = $request;
    $this->request_report_type = $request->request_report_type;
    $this->data = $request->data;
    $this->report_type = $request->report_type;
    $this->role_group = $request->role_group;

  }

  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {

    $data_result = $this->data;

    // =========== for sum row =================================
    if ($this->request_report_type == 'report' && $this->role_group == 'admin' && count($this->data) > 1) {
      $row = $this->report_sum();

      array_push($data_result, $row);
    }

    return collect($data_result);

  }

  public function headings(): array
  {

    $head = [
      'Ստանդարտ տ․',
      'Զեղչված տ․',
      'Անվճար տ․',
      'Միասնական տ․ ըստ թանգարանների',
      'Անդամակցության քարտ',
      'Ցուցադրություն',
      'Միջոցառման տ․',
      'Կորպորատիվ',
      'Կրթական ծրագիր',
      'Էքսկուրսիա',
      'Չեղարկված',
      'Ապրանքներ',
      'Այլ ծառայություններ'
    ];

    if ($this->role_group == 'admin') {
      array_unshift($head, 'Թանգարան');
    }

    if ($this->request_report_type == 'compare') {
      array_push($head, 'Ամսաթիվ');
    }

    return $head;
  }

  public function styles(Worksheet $sheet)
  {
    return [
      // Style the first row as bold text.
      1 => ['font' => ['bold' => true]],
    ];
  }

  public function map($data): array
  {

    if ($this->role_group == 'admin') {
      $name = isset($data['museum_id']) ? getMuseum($data['museum_id'])->translationsForAdmin->name : ($this->request_report_type == 'report' ? ' Ընդամենը ' : ' - ');
      $report['name'] = $name;
    }

    foreach (reportTypes() as $type) {

      if ($this->report_type == 'fin_quant' || $this->report_type == null) {
        $item = !empty($data[$type]) ? $data[$type]['total_price'] . ' / ' . $data[$type]['quantity'] : ' - ';
      } else if ($this->report_type == 'financial') {
        $item = !empty($data[$type]) ? $data[$type]['total_price'] : ' - ';
      } else {
        $item = !empty($data[$type]) ? $data[$type]['quantity'] : ' - ';
      }

      $report[$type] = $item;
    }

    if ($this->request_report_type == 'compare') {
      $item = !empty($data['start_date']) ? date('d.m.Y', strtotime($data['start_date'])) . ' - ' . date('d.m.Y', strtotime($data['end_date'])) : ' - ';
      $report['date'] = $item;
    }

    return $report;
  }

  public function report_sum()
  {

    $sums = reportResult($this->data);
    $report_type = $this->report_type;
    $sum_row_array = [];
    $all_report_types = reportTypes();

    if ($report_type == 'fin_quant' || $report_type == null) {

      foreach ($all_report_types as $type) {
        $sum_row_array[$type]['total_price'] = !empty($sums[$type]) ? $sums[$type]['total_price'] : ' - ';
        $sum_row_array[$type]['quantity'] = !empty($sums[$type]) ? $sums[$type]['quantity'] : ' - ';

      }
    } elseif ($report_type == 'financial') {

      foreach ($all_report_types as $type) {
        $sum_row_array[$type]['total_price'] = !empty($sums[$type]) ? $sums[$type]['total_price'] : ' - ';
      }
    } else {
      foreach ($all_report_types as $type) {
        $sum_row_array[$type]['quantity'] = !empty($sums[$type]) ? $sums[$type]['quantity'] : ' - ';

      }
    }

    return $sum_row_array;

  }


}
