<?php
namespace App\Traits\Museum\Tickets;

use App\Models\Ticket;
use App\Models\TicketType;
use App\Services\Log\LogService;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

trait UpdateOrCreateTrait
{
  abstract function model();
  public function itemUpdateOrCreate(Request $request)
  {

    $data = $request->all();
    $className = $this->model();

    if (class_exists($className)) {

      $model = new $className;
      $table_name = $model->getTable();

      if (in_array('museum_id', Schema::getColumnListing($table_name))) {
          $data['museum_id'] = museumAccessId();
      }

      if ($request['percent'] != null) {

          $coefficient = $request['percent'] / 100;
          $data['coefficient'] = $coefficient;
          TicketType::where('name', 'united')->update(['coefficient' => $coefficient]);
      }


      $item = $model::updateOrCreate(['id' => $request->id], $data);


      if ($item) {

        LogService::store($request->all(), Auth::id(), $table_name, 'store');

        return true;
      }

    }
  }




}
