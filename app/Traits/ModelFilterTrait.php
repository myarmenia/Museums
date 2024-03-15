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

      foreach ($filters as $field => $value) {
dd(array_search(['start_date'], $relationFilter));

      if(in_array($field, $this->boolFilterFields) && $value != null) {
              $builder->where($field, (bool)$value);
              continue;
          }
          if(!in_array($field, $defaultFillableFields) || !$value) {
              continue;
          }
          if(in_array($field, $this->likeFilterFields)) {
              $builder->where($tableName.'.'.$field, 'LIKE', "%$value%");
          }
          if (in_array($field, $this->relationFilter)) {
            dd(15);
            $builder->where($tableName . '.' . $field, 'LIKE', "%$value%");
          }
          else if(is_array($value)) {
              $builder->whereIn($field, $value);
          } else {
              $builder->where($field, $value);
          }
      }

        return $builder;


  }

}
