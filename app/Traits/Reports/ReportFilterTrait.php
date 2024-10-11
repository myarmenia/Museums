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
    $filterDateRangeFields = $this->filterDateRangeFields;
    $filterAgeRangeFields = $this->filterAgeRangeFields;


    foreach ($filters as $field => $value) {
      if($value != null){

            if($field == 'gender' && $value == 'unknown'){
              $value = null;
            }

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

            if (isset ($filterDateRangeFields) && in_array($field, $filterDateRangeFields)) {

              if ($field == "start_date") {
                $builder->whereDate('created_at', '>=', $value);

              }
              if ($field == "end_date") {
                $builder->whereDate('created_at', '<=', $value);

              }
            }

            if (isset($filterAgeRangeFields) && in_array($field, $filterAgeRangeFields)) {

              if ($field == "start_age") {
                $builder->where('age', '>=', $value);

              }
              if ($field == "end_age") {
                $builder->where('age', '<=', $value);

              }
            }


            if (isset($defaultFields) && in_array($field, $defaultFields) ) {



                if (is_array($value)) {
                    $builder->whereIn($field, $value);
                }
                else{
                    $builder->where($field, $value);
                }

                // if ($tableName == 'purchased_items') {
                //   $builder->orWhere('type', 'united');
                // }



            }



        if (isset($relationFilter) && $this->getKeyFromValue($field, $relationFilter)) {
              $relationModel = $this->getKeyFromValue($field, $relationFilter);

                $keys = array_keys($filters);
                $values = array_values($filters);

                $new_filters = array($field => $filters[$field]);

              if(count($relationModel) > 1){
                  foreach ($relationModel as $i => $p) {
                    // dump($p);
                    if($i == 0){

                        $builder->whereHas($p, function ($query) use ($new_filters) {
                          $query->reportFilter($new_filters);
                        });
                    }
                    else{
                        $builder->orWhereHas($p, function ($query) use ($new_filters) {
                          $query->reportFilter($new_filters);
                        });
                    }

                  }
              }
              else{
                  $builder->whereHas($relationModel[0], function ($query) use ($new_filters) {
                    $query->reportFilter($new_filters);
                  });
              }



            }

      }

      // dd($builder->get());


      // if ($filterFields && in_array($field, $filterFields) && $value != null) {

      //     $builder->where($field, $value);
      // }





    }

    return $builder;


  }

  // public function getKeyFromValue($needle, $haystack)
  // {
  //   $collection = new Collection($haystack);

  //   return $collection->search(function ($values) use ($needle) {

  //     return in_array($needle, $values);
  //   });
  // }


  function getKeyFromValue($needle, $haystack) {
    $keys = [];
    foreach ($haystack as $key => $valueArray) {
        if (in_array($needle, $valueArray)) {
            $keys[] = $key;
        }
    }

    return $keys;
}


}
