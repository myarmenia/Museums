<?php
namespace App\Traits;

use Illuminate\Http\Request;

trait FilterTrait {
    public function scopeFilter( $builder, $filters = []){

      $lang = request()->is('api/*') ? session('languages') : "am";

        if(!$filters) {
          return $builder;
      }
// dd($filters);
  $tableName = $this->getTable();
  $likeFilterFields = isset($this->likeFilterFields) ? $this->likeFilterFields : [];
  $defaultFillableFields = $this->filterFields;
  $filterFieldsInRelation = $this->filterFieldsInRelation;
  $hasRelationFields = isset($this->hasRelationFields)? $this->hasRelationFields : []; //fields  for relation
  $hasRelationTranslation = isset($this->hasRelationTranslation) ? $this->hasRelationTranslation :[];   // fields for model translation
// dd($likeFilterFields,$defaultFillableFields, $filterFieldsInRelation,$hasRelationFields, $hasRelationTranslationFields);
// dd($likeFilterFields);
// dd($defaultFillableFields);
// dd($filterFieldsInRelation);
// dd($hasRelationFields);
// dd($hasRelationTranslation,$filterFieldsInRelation);
$like_or_equal = null;

  foreach ($filters as $field => $value) {

        if( $value!=null) {

        if(in_array($field, $likeFilterFields)) {
            $builder->whereHas('product_translations', 'LIKE', "%$value%");
        }
        else if(is_array($value)) {
            $builder->whereIn($field, $value);
        }

        if(in_array($field,$defaultFillableFields) ){

          $builder->where($field, $value);
        }
        if ($field=="from_created_at"){

          $builder->whereDate('created_at', '>=', $value);

        }
        if ($field=="to_created_at"){

          $builder->whereDate('created_at', '<=', $value);

        }

        if(isset($hasRelationTranslation) && in_array($field,$filterFieldsInRelation)) {

          $name="item_translations";
          $search_name = "name";
          $action="LIKE";
          $data = '%'.$value.'%';
          $builder->whereHas($name, function ($query) use ($action, $data,  $search_name, $lang) {
              $query->where($search_name, $action, $data);
              $query->where('lang', $lang);

          });

        }
        if (isset($hasRelationFields) && in_array($field, $filterFieldsInRelation)) {

          $name="item_translations";
          $search_name = "name";
          $action="LIKE";
          $data = '%'.$value.'%';
          $builder->whereHas($name, function ($query) use ($action, $data,  $search_name) {
              $query->where($search_name, $action, $data);

          });

        }



      }




  }
  // dd($builder->get());
  return $builder;

  }




}
