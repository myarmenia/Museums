<?php
namespace App\Traits;

use App\Http\Requests\User\UserCreateOrUpdateRequest;
use App\Models\API\VerifyUser;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Mail\SendVerifyToken;
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

       $data = $this->model()::orderBy('id', 'DESC')->paginate(10);

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
            $token = sha1(Str::random(80));
            $email = $user->email;
            $verify = VerifyUser::create([
              'email' => $email,
              'verify_token' => $token
            ]);

            Mail::send(new SendVerifyToken($email, $token));
        }

        return redirect()->route("users.index")
          ->with('success', 'User created successfully');
    }


    // ========================= edit =========================
    public function edit($id)
    {

        $user = $this->model()::findOrFail($id);

        if (Auth::user()->hasRole('super_admin')) {
            $roles = Role::where('g_name', 'admin')->pluck('name', 'name')->all();

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

        return view('users.show', compact('user'));
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
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
        ->with('success', 'User updated successfully');
    }

}
