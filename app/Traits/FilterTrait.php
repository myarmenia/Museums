<?php
namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;


trait FilterTrait {

  public function scopeFilter($builder, $filters = [])
  {

      $lang = request()->is('api/*') ? session('languages') : "am";

      if(!$filters) {
            return $builder;
      }

      $tableName = $this->getTable();
      $filterFields = isset($this->filterFields) ? $this->filterFields : false;
      $defaultFillableFields = $this->defaultFillableFields;
      $relationFilter = $this->relationFilter;
      $hasRelationTranslation = isset ($this->hasRelationTranslation) ? $this->hasRelationTranslation : false;   // fields for model translation
      $filterFieldsInRelation = isset ($this->filterFieldsInRelation) ? $this->filterFieldsInRelation : false;
      $filterDateRangeFields = $this->filterDateRangeFields;
      $hasRelation = isset ($this->hasRelation) ? $this->hasRelation : false;  //  relation name array

      foreach ($filters as $field => $value) {

          if(isset($this->boolFilterFields) && in_array($field, $this->boolFilterFields) && $value != null) {
              $builder->where($field, (bool)$value);
              continue;
          }


          if(isset($this->likeFilterFields) && in_array($field, $this->likeFilterFields)) {
              $builder->where($tableName.'.'.$field, 'LIKE', "%$value%");
          }
          if($filterFields && in_array($field, $filterFields)   && $value!=null ){

              $builder->where($field, $value);
          }

          if ($field == "from_created_at" && $value != null) {

            $builder->whereDate('created_at', '>=', $value);

          }
          if ($field == "to_created_at" && $value != null) {

            $builder->whereDate('created_at', '<=', $value);

          }
          if ($hasRelationTranslation && $filterFieldsInRelation && in_array($field, $filterFieldsInRelation)) {

            $name = "item_translations";
            $search_name = "name";
            $action = "LIKE";
            $data = '%' . $value . '%';
            $builder->whereHas($name, function ($query) use ($action, $data, $search_name, $lang) {
              $query->where($search_name, $action, $data);
              $query->where('lang', $lang);

            });

          }

          if (isset ($filterDateRangeFields)) {

              if ($field == "start_date") {
                $builder->where('end_date', '>=', $value);

              }
              if ($field == "end_date") {
                $builder->where('start_date', '<=', $value);

              }
          }

          if(request()->type == 'subscription' || request()->type == 'standart'){
              $relationModel = request()->type == 'subscription' ? 'subscription_tickets' : 'standart_tickets';
              $builder->whereHas($relationModel, function ($query) use ($filters) {
                $query->where('status', 1);
              });
          }

          if (isset ($relationFilter) && $this->getKeyFromValue($field, $relationFilter)) {
              $relationModel  = $this->getKeyFromValue($field, $relationFilter);

                $builder->whereHas($relationModel, function ($query) use ($filters) {
                    $query->filter($filters);
                });

          }

          if($hasRelation){
                foreach($hasRelation as $key=>$rel){
dump($key,$rel);
                  if($rel && in_array($field,  $filterFieldsInRelation)){

                                  $name = $field;
                                  $search_name = $field;
                                  $action = "=";
                                  $builder->whereHas($rel, function ($query) use ($action, $search_name, $value) {
                                      $query->where($search_name, $action, $value);

                                  });

                                }


                }
          }

          if (isset ($defaultFillableFields) && in_array($field, $defaultFillableFields)) {
              $builder->where($field, $value);
          }
          else if(is_array($value)) {
              $builder->whereIn($field, $value);
          }
          //  else {
          //     $builder->where($field, $value);
          // }
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
