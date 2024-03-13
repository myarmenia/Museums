<?php

namespace App\Http\Controllers\Admin\MuseumBranches;

use App\Http\Controllers\Controller;
use App\Http\Requests\MuseumBranchRequest;
use App\Interfaces\MuseumBranches\MuseumBranchesRepositoryInterface;
use App\Models\Museum;
use App\Models\MuseumStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MuseumBranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $museumBranchRepository;
    public function __construct(MuseumBranchesRepositoryInterface $museumBranchRepository){

      $this->middleware('role:museum_admin');
      $this->middleware('museum_branch_middleware')->only(['edit','update']);
      $this->museumBranchRepository = $museumBranchRepository;

    }
    public function index()
    {

      $museum = MuseumStaff::where('user_id',Auth::id())->first();


        $museum_branches = $this->museumBranchRepository->all();
        // if($museum_branches==false){
        //   return view("content.museum-branches.index", compact('museum_branches'));

        // }


        return view("content.museum-branches.index", compact('museum_branches'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      $data =$this->museumBranchRepository->creat();
      return view("content.museum-branches.create", compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(MuseumBranchRequest $request)
    {

      $branches_created = $this->museumBranchRepository->store($request->all());
      if($branches_created){

        $museum_branches = $this->museumBranchRepository->all();


        return redirect()->route('branches-list');
      }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

      $data = $this->museumBranchRepository->find($id);

      if( $data!=null && $data->museum_id==museumAccessId()){
        return view("content.museum-branches.edit", compact('data'));
      }else{
        return redirect()->back();
      }



    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MuseumBranchRequest $request, string $id)
    {

        $data = $this->museumBranchRepository->update($request->all(),$id);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
