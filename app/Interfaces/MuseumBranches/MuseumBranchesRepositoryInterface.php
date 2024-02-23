<?php

namespace App\Interfaces\MuseumBranches;
interface MuseumBranchesRepositoryInterface {
  public function all();
  public function creat();
  public function store($request);
  public function find($id);
  public function update($request,$id);


}

