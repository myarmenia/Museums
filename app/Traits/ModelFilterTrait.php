<?php
namespace App\Traits;

use Illuminate\Http\Request;

trait ModelFilterTrait
{
  public function scopeFilter($builder, $filters = [])
  {

      if(!$filters) {
            return $builder;
      }

      $tableName = $this->getTable();
      $defaultFillableFields = $this->fillable;
      $relationFilter = $this->relationFilter;
      $filterDateFields = $this->filterDateFields;


      foreach ($filters as $field => $value) {
if(isset($relationFilter)){
  foreach ($relationFilter as $relation) {
    $builder->whereHas($relation, function ($query) use ($filters) {
        $query->filter($filters);
    });
}
}


      // if(in_array($field, $this->boolFilterFields) && $value != null) {
      //         $builder->where($field, (bool)$value);
      //         continue;
      //     }
      //     if(!in_array($field, $defaultFillableFields) || !$value) {
      //         continue;
      //     }
      //     if(in_array($field, $this->likeFilterFields)) {
      //         $builder->where($tableName.'.'.$field, 'LIKE', "%$value%");
      //     }
          if(isset($filterDateFields) && in_array($field, $this->filterDateFields)) {
            $builder->where($field,'>=', $value);
        }

          // else if(is_array($value)) {
          //     $builder->whereIn($field, $value);
          // } else {
          //     $builder->where($field, $value);
          // }
      }

        return $builder;


  }

}
