@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')
@section('page-script')
    <script src="{{ asset('assets/js/change-status.js') }}"></script>
    <script src="{{ asset('assets/js/admin/users/student-is-present.js') }}"></script>
    <script src="{{ asset('assets/js/delete-item.js') }}"></script>
@endsection

@section('content')

    <h4 class="py-3 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);">Լոգավորում</a>
                </li>
                <li class="breadcrumb-item active">Ցանկ</li>
            </ol>
        </nav>
    </h4>
    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-header">Գործողությունների ցանկ</h5>
            </div>

        </div>
        <div class="card-body">

            <div>
                <form action="{{route('users_visitors')}}" method="get" class="row g-3 mt-2" style="display: flex">
                    <div class="mb-3 justify-content-end" style="display: flex; gap: 8px">

                      <div class="new-select rounded" id="">
                                <div class="new-select-title-box">
                                    <input type="text" value="{{ request()->input('role') ?? ''}}" hidden name="role" />
                                    <span role="select-title">{{__('selects.roles')}}</span>
                                    <img class="new-select-icon" src="{{asset('assets/images/select-chev.svg')}}" />
                                </div>
                                <div class="new-select-options-box">
                                    <ul>
                                        <li data-value="">{{__('selects.all')}}</li>

                                        @foreach($roles as $key => $role)
                                            <li data-value="{{$role}}">{{$role}}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        <div class="col-2">
                            <input type="text" class="form-control" id="role" placeholder="Դեր" name="role" value="{{ request()->input('role') }}">
                        </div>

                        <div class="col-2">
                            <input type="text" class="form-control" id="date" placeholder="Ամսատիվ" name="date" value="{{ request()->input('phone') }}">
                        </div>

                        <button class="btn btn-primary col-2">Որոնել</button>

                    </div>
                </form>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Օգտագործող</th>
                            <th>Դեր</th>
                            <th>Գործ․ տեսակ</th>
                            <th>Գործ․ օբեկտ</th>
                            <th>Տվյալներ</th>
                            <th>Ամսատիվ</th>
                            {{-- <th>Գործողություն</th> --}}
                        </tr>
                    </thead>
                    <tbody>

                        {{-- @foreach ($data as $key => $log)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $log->user->name }} {{ $log->user->surname }}</td>
                                <td>{{ implode(', ', $log->roles->pluck('name')->toArray()) }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>


                                <td>
                                    @if (!empty($user->getRoleNames()))
                                        @foreach ($user->getRoleNames() as $v)
                                            <label>{{ __("roles.$v") }}</label>
                                        @endforeach
                                    @endif
                                </td>

                                <td>
                                    <div class="dropdown action" data-id="{{ $user->id }}" data-tb-name="users">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>

                                        <div class="dropdown-menu">

                                            <a class="dropdown-item d-flex" href="javascript:void(0);">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input change_status" type="checkbox"
                                                        role="switch" data-field-name="status"
                                                        {{ $user->status ? 'checked' : null }}>
                                                </div>Կարգավիճակ
                                            </a>
                                            <a class="dropdown-item" href="{{route('users.edit', $user->id)}}"><i
                                                class="bx bx-edit-alt me-1"></i>Փոփոխել
                                            </a>
                                            <button type="button" class="dropdown-item click_delete_item"
                                                data-bs-toggle="modal" data-bs-target="#smallModal"><i
                                                    class="bx bx-trash me-1"></i>
                                                Ջնջել
                                            </button>

                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
            <div class="demo-inline-spacing">
                {{ $data->links() }}
            </div>
        </div>
    </div>


@endsection

<x-modal-delete></x-modal-delete>
