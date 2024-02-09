@extends('layouts/contentNavbarLayout')

@section('title', ' Horizontal Layouts - Forms')

@section('content')
<h4 class="py-3 mb-4">
  <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
          <li class="breadcrumb-item">
              <a href="{{route('lesson-list')}}">Уроки</a>
          </li>
          <li class="breadcrumb-item active">Редактировать</li>
      </ol>
  </nav>
</h4>



<!-- Basic Layout & Basic with Icons -->
<div class="row">
  <!-- Basic Layout -->
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Редактировать урок</h5>
         {{-- <small class="text-muted float-end">Default label</small> --}}
      </div>
      <div class="card-body">
        <form  action="{{ route('lesson-update', $lesson->id )}}" method="post" enctype="multipart/form-data">
          @csrf
          @method('PUT')
            <!-- Dropdown with icon -->
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label" for="basic-default-name">Название языка</label>
              <div class="col-sm-10">

                <select class="form-select" name="course_language_id" id="exampleFormControlSelect1" aria-label="Default select example">
                  <option disabled> Название языка</option>
                  @if (count($course_language)>0)
                    @foreach ($course_language as $item)
                        @if ($lesson->course_language_id==$item->id)
                            <option value="{{ $item->id }}" selected>{{$item->name}}</option>
                        @else
                          <option value="{{ $item->id }}">{{$item->name}}</option>
                        @endif
                    @endforeach

                  @else
                    <option value="">Нет записей</option>
                  @endif
                </select>
              </div>
            </div>
            <!--/ Dropdown with icon -->

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="basic-default-name">Продолжительность</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="duration" value="{{$lesson->duration}}" id="basic-default-name" placeholder="Продолжительность" />
              @if($errors->has("duration"))
                <div class="text-danger mt-2">{{ $errors->first("duration") }}</div>
              @endif

            </div>
          </div>
          {{-- {{dd(languages())}} --}}
          @foreach (languages() as $lang )

            <div class="row mb-3">
              <label class="col-sm-2 col-form-label" for="basic-default-name">Название {{$lang}}</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="translate[{{$lang}}][title]"  value="{{ $lesson->translation($lang)->title }}" id="title" placeholder="Название" />
                @if($errors->has("translate.$lang.title"))
                  <div class="text-danger mt-2">{{ $errors->first("translate.$lang.title") }}</div>
                @enderror
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label" for="basic-default-name">Описание {{$lang}}</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="translate[{{$lang}}][description]" value="{{ $lesson->translation($lang)->description }}" id="description" placeholder="Описание" />

                @if($errors->has("translate.$lang.description"))
                  <div class="text-danger mt-2">{{ $errors->first("translate.$lang.description")  }}</div>
                @endif
              </div>
            </div>
          @endforeach


          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="basic-default-name">видео</label>
            <div class="col-sm-10">
              <input type="file" class="form-control" name="video" id="description" placeholder="видео" />

              @if($errors->has("video"))
                <div class="text-danger mt-2">{{ $errors->first("video")  }}</div>
              @endif
              <video width="240" height="240" controls>
                <source src="{{route('get-file',['path'=> $lesson->video])}}" type="video/mp4">
              </video>
            </div>

          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="basic-default-name">Номер урока</label>
            <div class="col-sm-10">
              <input type="number" class="form-control" name="number" value="{{$lesson->number}}" id="description" placeholder="Номер урока" />
              @if($errors->has("number"))
                <div class="text-danger mt-2">{{ $errors->first("number")  }}</div>
              @endif
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
  </div>

</div>
@endsection
