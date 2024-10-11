<?php
namespace App\Traits\Dashboard;

use App\Models\Museum;
use App\Models\MuseumTranslation;
use App\Models\Purchase;
use App\Models\PurchasedItem;
use App\Models\PurchaseUnitedTickets;
use Illuminate\Support\Facades\DB;

// trait SingleMuseumAnalyticsTrait
// {

//   public function analyticsByMonth($museum_id)
//   {
//     $currentYear = now()->year;

//     $purchases_ids = Purchase::where('status', 1)->whereYear('created_at', $currentYear)->pluck('id');
//     $purchased_items = PurchasedItem::whereIn('purchase_id', $purchases_ids);

//     $analytic = $purchased_items->where('museum_id', $museum_id)->where('type', '!=', 'united');

//     $purchased_items = PurchasedItem::whereIn('purchase_id', $purchases_ids);
//     $purchased_item_ids = $purchased_items->where('type', 'united')->pluck('id');


//     $united = [];
//     $united = PurchaseUnitedTickets::whereIn('purchased_item_id', $purchased_item_ids)->where('museum_id', $museum_id);

//     $groupedData = $this->analytic_financial_by_month($analytic, $united);
//     return $groupedData;

//   }

//   public function analytic_financial_by_month($analytic, $united)
//   {


//       $analytic = $analytic->selectRaw('MONTH(created_at) as month, SUM(total_price - returned_total_price) as total_price')
//         ->whereYear('created_at', date('Y'))
//         ->groupByRaw('MONTH(created_at)')
//         ->get();

//       $united = $united->selectRaw('MONTH(created_at) as month, SUM(total_price) as total_price')
//         ->whereYear('created_at', date('Y'))
//         ->groupByRaw('MONTH(created_at)')
//         ->get();


//       $analytic = $analytic->toArray();
//       $united = $united->toArray();


//       return $this->analyticgroupByMonth(array_merge($analytic, $united));

//   }
//   public function analyticgroupByMonth($data)
//   {

//       $groupedData = [
//           '1' => 0,
//           '2' => 0,
//           '3' => 0,
//           '4' => 0,
//           '5' => 0,
//           '6' => 0,
//           '7' => 0,
//           '8' => 0,
//           '9' => 0,
//           '10' => 0,
//           '11' => 0,
//           '12' => 0
//       ];

//       foreach ($data as $key => $item) {
//           $month_number = $item['month'];

//           if (isset($groupedData[$month_number])) {
//               $groupedData[$month_number] += $item['total_price'];
//           }
//           else {
//               $groupedData[$month_number] = $item['total_price'];
//           }
//       }

//       return [
//         'total_prices' => array_values($groupedData),
//         'item_names' => getMonths()
//       ];

//   }


// }

// ====================== 30.09.2024 opti alacum ===============================
trait SingleMuseumAnalyticsTrait
{
    public function analyticsByMonth($museum_id)
    {
        $currentYear = now()->year;

        // Получаем идентификаторы покупок за текущий год
        $purchases_ids = Purchase::where('status', 1)
            ->whereYear('created_at', $currentYear)
            ->pluck('id');

        // Получаем приобретенные предметы для данного музея, исключая тип 'united'
        $analytic = PurchasedItem::whereIn('purchase_id', $purchases_ids)
            ->where('museum_id', $museum_id)
            ->where('type', '!=', 'united');

        // Получаем идентификаторы приобретенных предметов типа 'united'
        $purchased_item_ids = PurchasedItem::whereIn('purchase_id', $purchases_ids)
            ->where('type', 'united')
            ->pluck('id');

        // Получаем связанные билеты типа 'united' для данного музея
        $united = PurchaseUnitedTickets::whereIn('purchased_item_id', $purchased_item_ids)
            ->where('museum_id', $museum_id);

        // Анализируем финансовую информацию по месяцам
        return $this->analytic_financial_by_month($analytic, $united);
    }

    public function analytic_financial_by_month($analytic, $united)
    {
        // Получаем данные по аналитике за текущий год, сгруппированные по месяцам
        $analytic = $analytic->selectRaw('MONTH(created_at) as month, SUM(total_price - returned_total_price) as total_price')
            ->whereYear('created_at', now()->year)
            ->groupByRaw('MONTH(created_at)')
            ->get()
            ->toArray();

        // Получаем данные по билетам типа 'united', сгруппированные по месяцам
        $united = $united->selectRaw('MONTH(created_at) as month, SUM(total_price) as total_price')
            ->whereYear('created_at', now()->year)
            ->groupByRaw('MONTH(created_at)')
            ->get()
            ->toArray();

        // Объединяем данные аналитики и данных по билетам и группируем по месяцам
        return $this->analyticgroupByMonth(array_merge($analytic, $united));
    }

    public function analyticgroupByMonth($data)
    {
        // Инициализируем массив для всех месяцев с нулевыми значениями
        $groupedData = array_fill(1, 12, 0);

        // Группируем данные по месяцам и складываем сумму для каждого месяца
        foreach ($data as $item) {
            $month_number = $item['month'];
            $groupedData[$month_number] += $item['total_price'];
        }

        // Возвращаем итоговые суммы и названия месяцев
        return [
            'total_prices' => array_values($groupedData),
            'item_names' => getMonths()  // Метод для получения названий месяцев
        ];
    }
}
