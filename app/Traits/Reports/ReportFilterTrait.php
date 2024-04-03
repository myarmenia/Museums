<?php
namespace App\Traits\Reports;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;


trait ReportFilterTrait
{

  public function scopeReportFilter($builder, $filters = [])
  {

    $lang = request()->is('api/*') ? session('languages') : "am";

    if (!$filters) {
      return $builder;
    }

    $tableName = $this->getTable();
    $filterFields = isset($this->filterFields) ? $this->filterFields : false;
    $defaultFields = $this->defaultFields;
    $relationFilter = $this->relationFilter;
    $filterFieldsInRelation = isset($this->filterFieldsInRelation) ? $this->filterFieldsInRelation : false;
    $hasRelation = isset($this->hasRelation) ? $this->hasRelation : false;  //  relation name array

    foreach ($filters as $field => $value) {
      if($value != null){

            if (isset($this->boolFilterFields) && in_array($field, $this->boolFilterFields)) {
              $builder->where($field, (bool) $value);
              continue;
            }

            if ($field == "from_created_at") {

              $builder->whereDate('created_at', '>=', $value);

            }

            if ($field == "to_created_at") {

              $builder->whereDate('created_at', '<=', $value);

            }

            if (isset($defaultFields) && in_array($field, $defaultFields)) {
                if (is_array($value)) {
                    $builder->whereIn($field, $value);
                }
                else{
                    $builder->where($field, $value);

                }
            }


      }




      // if ($filterFields && in_array($field, $filterFields) && $value != null) {

      //     $builder->where($field, $value);
      // }


      if (isset($relationFilter) && $this->getKeyFromValue($field, $relationFilter)) {
          $relationModel = $this->getKeyFromValue($field, $relationFilter);

          $builder->whereHas($relationModel, function ($query) use ($filters) {
            $query->filter($filters);
          });

      }


    }

    return $builder;


  }

  public function getKeyFromValue($needle, $haystack)
  {
    $collection = new Collection($haystack);

    return $collection->search(function ($values) use ($needle) {
      return in_array($needle, $values);
    });
  }


}
