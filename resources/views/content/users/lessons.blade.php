@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')
@section('page-script')
<script>
    let student_id = {{ $student->id }}
</script>
<script src="{{ asset('assets/js/admin/users/open-next-lesson.js') }}"></script>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script src="{{ asset('assets/js/admin/calendar.js') }}"></script>
{{-- <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'> --}}
<link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/bootstrap5@6.1.10/index.global.min.js'></script>
<link href="{{ asset('assets/css/admin/calendar.css') }}" rel='stylesheet' />


@endsection

@section('content')

<h4 class="py-3 mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('users.index')}}">Пользователи</a>
            </li>
            <li class="breadcrumb-item active">Уроки студента</li>
        </ol>
    </nav>
</h4>
<div class="card">

    <div class="card-body" id="user_id" data-id="{{ $student->id }}">
        <div class="row d-flex justify-content-between">
            <div class="demo-inline-spacing col-md-4">

                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">Имя
                        <span>{{ $student->name }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">Э. почта
                        <span>{{ $student->email }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">Телефон
                        <span>{{ $student->phone ?? '' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">Статус
                        @if ($student->status)
                        <span class="badge bg-label-success me-1">Активный</span>
                        @else
                        <span class="badge bg-label-danger me-1">Не активный</span>
                        @endif
                    </li>
                     <li class="list-group-item d-flex justify-content-between align-items-center">Количество уроков в месяц
                         <span>{{ $student->lessons_quantity_per_month ?? '' }}</span>
                     </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">Роли
                        <span>
                            @if (!empty($student->getRoleNames()))
                            @foreach ($student->getRoleNames() as $v)
                            <label>{{ $v }}</label>
                            @endforeach
                            @endif
                        </span>
                    </li>
                </ul>
            </div>


            <div class="col-md-4 d-flex align-items-end">
                <div class="w-100 ">
                    <form action={{ route('open_course', $student->id) }} method="post">
                        <div class="d-flex justify-content-between ">
                            <div class=" ">
                                <label for="course_languages" class="col-form-label">Добавить язык
                                    программирования</label>
                                <select class="form-select" id="course_languages" name="course_language_id">
                                    <option disabled selected> Языки</option>
                                    @if ($course_languages != null && count($course_languages) > 0)
                                    @foreach ($course_languages as $language)
                                    @if (!in_array($language->id, $user_course_langages))
                                    <option value="{{ $language->id }}">{{ $language->name }}</option>
                                    @endif
                                    @endforeach
                                    @endif

                                </select>
                            </div>
                            <button class="btn btn-primary h-75 align-self-end">Открить курс</button>

                        </div>
                    </form>
                </div>

            </div>
            @if (Session::has('message'))
            <div class="mt-2 row justify-content-end">
                <div class="col-sm-10 text-primary text-end fts-14">{{Session::get('message')}} </div>
            </div>
            @endif

        </div>
        <div class="table-responsive text-nowrap mt-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Язык прг.</th>
                        <th>Кол. уроков</th>
                        <th>Номер текущего урока</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($student->user_course_menegments as $key => $item)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $item->course_languages->name }}</td>
                        <td>{{ $item->course_languages->lessons->count() }}</td>
                        <td class="lesson_number">{{ $item->lesson_number }}</td>

                        <td>
                            <div class="dropdown action" data-id="{{ $item->course_languages->id }}">

                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    @if ($item->course_languages->lessons->count() != $item->lesson_number)
                                    <a class="dropdown-item open-next-lesson" href="javascript:void(0);">
                                        <i class="tf-icons bx bx-task"></i> Открить следующий урок
                                    </a>
                                    @else<a class="dropdown-item " href="javascript:void(0);">
                                        <i class="tf-icons bx bx-task"></i> Все уроки открыты
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- =============================== --}}
        <div id='calendar'></div>


    </div>


</div>

{{-- <script defer src='https://static.cloudflareinsights.com/beacon.min.js' data-cf-beacon='{"token": "dc4641f860664c6e824b093274f50291"}'></script><!-- Cloudflare Pages Analytics --> --}}
@endsection
