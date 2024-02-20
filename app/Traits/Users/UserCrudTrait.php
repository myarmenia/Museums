<?php
namespace App\Traits\Users;

use App\Http\Requests\User\UserCreateOrUpdateRequest;
use App\Mail\SendPassToEmployee;
use App\Models\API\VerifyUser;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Mail;
use Illuminate\Support\Str;


trait UserCrudTrait
{
  abstract function model();
  // abstract function validationRules();

  // public function new_model(){
  //     return new($this->model());
  // }

  // public function get_table_name()
  // {
  //     $item = $this->new_model();
  //     return  $item->getTable();
  // }
  // ========================= index =========================
  public function index(Request $request)
  {

    if (Auth::user()->hasRole('super_admin')) {
      $roles = $request->route()->getName() == 'users_visitors' ? ['web'] : ['admin', 'super_admin'];

    }

    if (Auth::user()->hasRole('museum_admin')) {
      $roles = ['museum'];
    }

    $data = $data = $this->model()::whereHas('roles', function ($query) use ($roles) {
        $query->whereIn('g_name', $roles);
      }
    );

    if ($request->name != null) {

      $data = $data->where('name', 'LIKE', "%{$request->name}%");
    }

    if ($request->surname != null) {

      $data = $data->where('surname', 'LIKE', "%{$request->surname}%");
    }

    if ($request->email != null) {

      $data = $data->where('email', 'LIKE', "%{$request->email}%");
    }

    if ($request->phone != null) {
      $data = $data->where('phone', $request->phone);
    }

    $data = $data->orderBy('id', 'DESC')->paginate(10)->withQueryString();



    return view("content.users.index", compact('data'))
      ->with('i', ($request->input('page', 1) - 1) * 10);

  }

  // ========================= create =========================
  public function create()
  {

    if (Auth::user()->hasRole('super_admin')) {
      $roles = Role::where('g_name', 'admin')->pluck('name', 'name')->all();

    }
    if (Auth::user()->hasRole('museum_admin')) {
      $roles = Role::where('g_name', 'museum')->pluck('name', 'name')->all();

    }

    return view('content.users.create', compact('roles'));
  }


  // ========================= store =========================
  public function store(UserCreateOrUpdateRequest $request)
  {

    $input = $request->all();

    $input['status'] = isset($request->status) ? true : 0;
    $input['password'] = Hash::make($input['password']);

    $user = $this->model()::create($input);
    $user->assignRole($request->input('roles'));

    if ($user) {
      $email = $user->email;
      $data = [
        'password' => $request->password,
        'email' => $email
      ];

      Mail::send(new SendPassToEmployee($email, $data));
    }

    return redirect()->route("users.index")
      ->with('success', 'User created successfully');
  }


  // ========================= edit =========================
  public function edit($id)
  {

    $user = $this->model()::findOrFail($id);

    if (Auth::user()->hasRole('super_admin')) {
      $roles = Role::whereIn('g_name', ['admin', 'super_admin'])->pluck('name', 'name')->all();

    }
    if (Auth::user()->hasRole('museum_admin')) {
      $roles = Role::where('g_name', 'museum')->pluck('name', 'name')->all();

    }

    $userRole = $user->roles->pluck('name', 'name')->all();

    return view('content.users.edit', compact('user', 'roles', 'userRole'));
  }


  // ========================= show =========================
  public function show($id)
  {
    $user = $this->model()::find($id);

    return view('content.users.show', compact('user'));
  }


  // ========================= update =========================
  public function update(UserCreateOrUpdateRequest $request, $id)
  {


    $input = $request->all();
    $input['status'] = isset($request->status) ? true : 0;


    if (!empty($input['password'])) {
      $input['password'] = Hash::make($input['password']);

    } else {
      $input = Arr::except($input, array('password'));
    }

    $user = $this->model()::find($id);
    $user_email = $user->email;
    $user->update($input);
    DB::table('model_has_roles')->where('model_id', $id)->delete();

    $user->assignRole($request->input('roles'));

    if (!empty($input['password']) || ($user_email != $input['email'])) {
      $email = $user->email;
      $data = [
        'password' => $request->password,
        'email' => $email
      ];

      Mail::send(new SendPassToEmployee($email, $data));
    }

    return redirect()->route('users.index')
      ->with('success', 'User updated successfully');
  }

}
