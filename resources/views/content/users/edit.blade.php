@extends('layouts/contentNavbarLayout')
@section('content')

<h4 class="py-3 mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('users.index')}}">Օգտագործողներ</a>
            </li>
            <li class="breadcrumb-item active">Փոփոխել օգտատիրոջը</li>
        </ol>
    </nav>
</h4>

<div class="card">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="card-header">Փոփոխել օգտատիրոջը</h4>
        </div>

    </div>
    <div class="card-body">

        <form action="{{route('users.update', $user->id)}}" method="post">
            @method('patch')
            <div class="mb-3 row">
                <label for="name" class="col-md-2 col-form-label">Անուն</label>
                <div class="col-md-10">
                    <input class="form-control" type="text" placeholder="Անուն" id="name" name="name" value="{{$user->name}}">
                </div>
            </div>
            @error('name')
            <div class="mb-3 row justify-content-end">
                <div class="col-sm-10 text-danger fts-14">{{$message}}
                </div>
            </div>
            @enderror

            <div class="mb-3 row">
                <label for="surname" class="col-md-2 col-form-label">Ազգանուն</label>
                <div class="col-md-10">
                    <input class="form-control" type="text" placeholder="Ազգանուն" id="surname" name="surname" value="{{$user->surname}}">
                </div>
            </div>
            @error('surname')
            <div class="mb-3 row justify-content-end">
                <div class="col-sm-10 text-danger fts-14">{{$message}}
                </div>
            </div>
            @enderror
            <div class="mb-3 row">
                <label for="email" class="col-md-2 col-form-label">Էլ․ հասցե</label>
                <div class="col-md-10">
                    <input class="form-control" type="search" placeholder="Էլ․ հասցե" id="email" name="email" value="{{$user->email}}">

                </div>
            </div>
            @error('email')
            <div class="mb-3 row justify-content-end">
                <div class="col-sm-10 text-danger fts-14">{{$message}}
                </div>
            </div>
            @enderror

            <div class="mb-3 row">
                <label for="phone" class="col-md-2 col-form-label">Հեռախոս</label>
                <div class="col-md-10">
                    <input class="form-control" type="text" placeholder="Телефон" id="phone" name="phone" value="{{$user->phone}}">

                </div>
            </div>

            <div class="mb-3 row form-password-toggle">

                <label class="col-md-2 col-form-label" for="password">Գաղտնաբառ</label>
                <div class="col-md-10 ">
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" placeholder="Գաղտնաբառ" aria-describedby="basic-default-password2" name="password">
                        <span id="basic-default-password2" class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                    </div>
                </div>
            </div>

            @error('password')
            <div class="mb-3 row justify-content-end">
                <div class="col-sm-10 text-danger fts-14">{{$message}}
                </div>
            </div>
            @enderror

            <div class="mb-3 row form-password-toggle">
                <label class="col-md-2 col-form-label" for="confirm-password">Կրկնել գաղտնաբառը</label>
                <div class="col-md-10 ">
                    <div class="input-group">
                        <input type="password" class="form-control" id="confirm-password" placeholder="Կրկնել գաղտնաբառը" aria-describedby="basic-default-confirm-password2" name="confirm-password">

                        <span id="basic-default-confirm-password2" class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                    </div>
                </div>
            </div>


            <div class="mb-3 row">

                <label for="role" class="col-md-2 col-form-label">Դերեր</label>
                <div class="col-md-10">
                    <select class="form-select" id="roles" name="roles[]" multiple>
                        <option value="" disabled>Դերեր</option>
                        @foreach ($roles as $role)
                        <option value="{{ $role}}" {{in_array($role, $userRole) ? 'selected' : ''}}>{{ __("roles.$role") }}</option>


                        @endforeach

                    </select>
                </div>
            </div>

            @error('roles')
            <div class="mb-3 row justify-content-end">
                <div class="col-sm-10 text-danger fts-14">{{$message}}
                </div>
            </div>
            @enderror


            <div class="mb-3 row">
                <label for="html5-text-input" class="col-md-2 col-form-label"></label>
                <div class="d-flex col-md-10">
                    <div class="col-md-2 form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="status" {{$user->status ? 'checked' : ''}} name="status">
                        <label class="form-check-label" for="status">Կարգավիճակ</label>
                    </div>

                </div>
            </div>
            <div class="row justify-content-end">
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary">Պահպանել</button>

                </div>
            </div>

        </form>
    </div>


</div>


@endsection
