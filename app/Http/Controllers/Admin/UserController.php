<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// use App\Http\Requests\User\UserCreateOrUpdateRequest;
use App\Models\User;
// use App\Models\API\VerifyUser;
use App\Traits\UserCrudTrait;
// use Auth;
// use Illuminate\Http\Request;
// use Illuminate\Support\Arr;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Hash;
// use Spatie\Permission\Models\Role;
// use App\Mail\SendVerifyToken;
// use Mail;
// use Illuminate\Support\Str;

class UserController extends Controller
{
  use UserCrudTrait;

  public function model()
  {

    return User::class;
  }

  // public function validationRules()
  // {
  //     $request = new UserCreateOrUpdateRequest();
  //       return $request->rules();
  // }
  //     public function index(Request $request)
//     {
//         $data = $this->index();
// dd($data);
//         return view('content.users.index', compact('data'))
//         ->with('i', ($request->input('page', 1) - 1) * 10);
//     }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  // public function create()
  // {

  //   if (Auth::user()->hasRole('super_admin')) {
  //     $roles = Role::where('g_name', 'admin')->pluck('name', 'name')->all();

  //   }
  //   if (Auth::user()->hasRole('museum_admin')) {
  //     $roles = Role::where('g_name', 'museum')->pluck('name', 'name')->all();

  //   }

  //   return view('content.users.create', compact('roles'));
  // }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  // public function store(UserCreateOrUpdateRequest $request)
  // {
  //   // dd($request->all());
  //   // $this->validate($request, [
  //   //   'name' => 'required',
  //   //   'email' => 'required|email|unique:users,email',
  //   //   'password' => 'required|same:confirm-password|min:8',
  //   //   'lessons_quantity_per_month' => 'required',
  //   //   'phone' => 'required',
  //   //   'roles' => 'required'
  //   // ]);

  //   $input = $request->all();
  //   $input['status'] = isset($request->status) ? true : 0;   


  //   $input['password'] = Hash::make($input['password']);

  //   $user = User::create($input);
  //   $user->assignRole($request->input('roles'));
  //   // $user->assignRole(['student']);
  //   if ($user) {
  //     $token = sha1(Str::random(80));
  //     $email = $user->email;
  //     $verify = VerifyUser::create([
  //       'email' => $email,
  //       'verify_token' => $token
  //     ]);

  //     Mail::send(new SendVerifyToken($email, $token));
  //   }

  //   return redirect()->route('users.index')
  //     ->with('success', 'User created successfully');
  // }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  // public function show($id)
  // {
  //   $user = User::find($id);

  //   // return view('users.show', compact('user'));
  // }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  // public function edit($id)
  // {
  //   $user = User::find($id);
  //   $roles = Role::pluck('name', 'name')->all();
  //   $userRole = $user->roles->pluck('name', 'name')->all();

  //   return view('content.users.edit', compact('user', 'roles', 'userRole'));
  // }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  // public function update(Request $request, $id)
  // {

  //   $this->validate($request, [
  //     'name' => 'required',
  //     'email' => 'required|email|unique:users,email,' . $id,
  //     'password' => 'same:confirm-password',
  //     'lessons_quantity_per_month' => 'required',
  //     'phone' => 'required',
  //     'roles' => 'required'
  //   ]);

  //   $input = $request->all();
  //   $input['status'] = isset($request->status) ? true : 0;
  //   $input['payment_status'] = isset($request->payment_status) ? true : 0;
  //   $input['passport'] = isset($request->passport) ? true : 0;

  //   if (!empty($input['password'])) {
  //     $input['password'] = Hash::make($input['password']);
  //   } else {
  //     $input = Arr::except($input, array('password'));
  //   }

  //   $user = User::find($id);
  //   $user->update($input);
  //   DB::table('model_has_roles')->where('model_id', $id)->delete();

  //   $user->assignRole($request->input('roles'));

  //   return redirect()->route('users.index')
  //     ->with('success', 'User updated successfully');
  // }


  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    // User::find($id)->delete();
    // return redirect()->route('users.index')
    // ->with('success', 'User deleted successfully');
  }
}
