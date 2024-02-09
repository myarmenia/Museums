@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')

@section('content')


<h4 class="py-3 mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('users.index')}}">Пользователи</a>
            </li>
            <li class="breadcrumb-item active">Редактировать пользователя</li>
        </ol>
    </nav>
</h4>

<div class="card">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="card-header">Редактировать пользователя</h4>
        </div>

    </div>
    <div class="card-body">

        <form action="{{route('users.update', $user->id)}}" method="post">
            @method('patch')
            <div class="mb-3 row">
                <label for="name" class="col-md-2 col-form-label">Имя</label>
                <div class="col-md-10">
                    <input class="form-control" type="text" placeholder="Имя" id="name" name="name" value="{{$user->name}}">
                </div>
            </div>
            @error('name')
            <div class="mb-3 row justify-content-end">
                <div class="col-sm-10 text-danger fts-14">{{$message}}
                </div>
            </div>
            @enderror

            <div class="mb-3 row">
                <label for="email" class="col-md-2 col-form-label">Э. почта</label>
                <div class="col-md-10">
                    <input class="form-control" type="search" placeholder="Э. почта" id="email" name="email" value="{{$user->email}}">

                </div>
            </div>
            @error('email')
            <div class="mb-3 row justify-content-end">
                <div class="col-sm-10 text-danger fts-14">{{$message}}
                </div>
            </div>
            @enderror

            <div class="mb-3 row">
                <label for="phone" class="col-md-2 col-form-label">Телефон</label>
                <div class="col-md-10">
                    <input class="form-control" type="text" placeholder="Телефон" id="phone" name="phone" value="{{$user->phone}}">

                </div>
            </div>

            <div class="mb-3 row form-password-toggle">

                <label class="col-md-2 col-form-label" for="password">Пароль</label>
                <div class="col-md-10 ">
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" placeholder="Пароль" aria-describedby="basic-default-password2" name="password">
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
                <label class="col-md-2 col-form-label" for="confirm-password">Подтвердите пароль</label>
                <div class="col-md-10 ">
                    <div class="input-group">
                        <input type="password" class="form-control" id="confirm-password" placeholder="Подтвердите пароль" aria-describedby="basic-default-confirm-password2" name="confirm-password">

                        <span id="basic-default-confirm-password2" class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                    </div>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="lessons_quantity_per_month" class="col-md-2 col-form-label">Количество уроков в месяц</label>
                <div class="col-md-10">
                    <input class="form-control" type="text" placeholder="количество уроков в месяц" id="lessons_quantity_per_month" name="lessons_quantity_per_month" value="{{$user->lessons_quantity_per_month}}">


                </div>
            </div>
            @error('lessons_quantity_per_month')
            <div class="mb-3 row justify-content-end">
                <div class="col-sm-10 text-danger fts-14">{{$message}}
                </div>
            </div>
            @enderror

            <div class="mb-3 row">

                <label for="role" class="col-md-2 col-form-label">Роли</label>
                <div class="col-md-10">
                    <select class="form-select" id="roles" name="roles[]" multiple>
                        <option value="" disabled>Роли</option>
                        @foreach ($roles as $role)
                        <option value="{{ $role}}" {{in_array($role, $userRole) ? 'selected' : ''}}>{{ $role}}</option>


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


                        <label class="form-check-label" for="status">Статус</label>
                    </div>
                    <div class="col-md-2 form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="passport" {{$user->passport ? 'checked' : ''}} name="passport">
                        <label class="form-check-label" for="passport">Пасспорт</label>
                    </div>
                    <div class="col-md-4 form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="payment_status" {{$user->payment_status ? 'checked' : ''}} name="payment_status">
                        <label class="form-check-label" for="payment_status">Статус платежа</label>
                    </div>
                </div>
            </div>
            <div class="row justify-content-end">
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary">Сохранить</button>

                </div>
            </div>

        </form>
    </div>


</div>


@endsection
