<?php
namespace App\Traits;
trait FilterTrait {
  public function scopeFilter($builder, $filters = []){
    if(!$filters) {
      return $builder;
  }

  $tableName = $this->getTable();

  $defaultFillableFields = $this->filterFields;
  // dd($filters, $defaultFillableFields);
  foreach ($filters as $field => $value) {

      // if(in_array($field, $this->boolFilterFields) && $value != null) {
      //     $builder->where($field, (bool)$value);
      //     continue;
      // }
      if(!in_array($field, $defaultFillableFields) || !$value) {
          continue;
      }
      if(in_array($field, $this->likeFilterFields)) {
          $builder->where($tableName.'.'.$field, 'LIKE', "%$value%");
      } else if(is_array($value)) {
          $builder->whereIn($field, $value);
      } else {
          $builder->where($field, $value);
      }
  }
  // dd($builder);
  return $builder;

  }




}
