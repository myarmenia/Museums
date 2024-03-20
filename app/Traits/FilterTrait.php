<?php
namespace App\Traits;

use Illuminate\Http\Request;

trait FilterTrait {
    public function scopeFilter( $builder, $filters = []){

      $lang = request()->is('api/*') ? session('languages') : "am";

        if(!$filters) {
          return $builder;
      }

      $tableName = $this->getTable();

      $likeFilterFields = isset($this->likeFilterFields) ? $this->likeFilterFields : false;
      $filterFields = isset($this->filterFields) ? $this->filterFields : false;
      $filterFieldsInRelation = isset($this->filterFieldsInRelation) ? $this->filterFieldsInRelation : false;
      $hasRelationFields = isset($this->hasRelationFields)? $this->hasRelationFields : false; //fields  for relation
      $hasRelationTranslation = isset($this->hasRelationTranslation) ? $this->hasRelationTranslation : false;   // fields for model translation
      $hasRelation = isset($this->hasRelation) ? $this->hasRelation : false;  //  relation name array
      $like_or_equal = null;
      // dd($filters, $hasRelation, $filterFieldsInRelation);
      foreach ($filters as $field => $value) {
// dd($filters, $field,  $value);
            if( $value!=null) {

            if($likeFilterFields && in_array($field, $likeFilterFields)) {
                $builder->whereHas('product_translations', 'LIKE', "%$value%");
            }
            else if(is_array($value)) {
                $builder->whereIn($field, $value);
            }
// dd($field, $filterFields);
            if(in_array($field, $filterFields) ){
              // dd($field,$filterFields);

              $builder->where($field, $value);
            }
            if ($field=="from_created_at"){

              $builder->whereDate('created_at', '>=', $value);

            }
            if ($field=="to_created_at"){

              $builder->whereDate('created_at', '<=', $value);

            }

            if($hasRelationTranslation && $filterFieldsInRelation && in_array($field,$filterFieldsInRelation)) {

              $name="item_translations";
              $search_name = "name";
              $action="LIKE";
              $data = '%'.$value.'%';
              $builder->whereHas($name, function ($query) use ($action, $data,  $search_name, $lang) {
                  $query->where($search_name, $action, $data);
                  $query->where('lang', $lang);

              });

            }

            if($hasRelation && in_array($field, $hasRelation)) {

              $name = $field;
              $search_name = "name";
              $action = "LIKE";
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
