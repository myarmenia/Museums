@extends('layouts/contentNavbarLayout')

@section('title', ' Horizontal Layouts - Forms')
@section('page-script')
<script src="{{ asset('assets/js/upload-video.js') }}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
          <li class="breadcrumb-item">
              <a href="{{route('task-list')}}">Задания</a>
          </li>
          <li class="breadcrumb-item active">Список</li>
      </ol>
  </nav>
</h4>

<!-- Basic Layout & Basic with Icons -->
<div class="row">
  <!-- Basic Layout -->
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Создать задачу урока</h5>
         {{-- <small class="text-muted float-end">Default label</small> --}}
      </div>

        <div class="card-body">
          <form  action="{{route('task-store')}}" method="post" enctype='multipart/form-data'>
            @csrf
              <!-- Dropdown with icon -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="basic-default-name">Название языка</label>
                <div class="col-sm-10">
                  <select class="form-select" name="lesson_id" id="course_language" data-url="{{ route('get-course-lessons')}}" aria-label="Default select example">
                    @if (count($course_languages)>0)
                      @foreach ($course_languages as $item)
                        <option value="{{ $item->id }}">{{$item->name}}</option>
                      @endforeach
                    @else
                      <option value="">Нет записей</option>
                    @endif

                  </select>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="basic-default-name">Название урока</label>
                <div class="col-sm-10" id="lessons_div">
                  <select class="form-select" name="lesson_id" id="lessons_section" aria-label="Default select example">
                    @if (count($lessons)>0)
                      @foreach ($lessons as $item)
                        <option value="{{ $item->id }}">{{$item->lesson_translations[0]->title}}</option>
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
                <input type="text" class="form-control" name="duration" id="basic-default-name"  value="{{old('duration')}}"placeholder="Продолжительность" />

                @if($errors->has("duration"))
                  <div class="text-danger mt-2">{{ $errors->first("duration") }}</div>
                @endif
              </div>

            </div>
            @foreach (languages() as $lang)
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="basic-default-name">Описание {{$lang}}</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="translate[{{$lang}}][description]" value="{{old("translate.$lang.description")}}" id="description" placeholder="Описание" />

                  @if($errors->has("translate.$lang.description"))
                    <div class="text-danger mt-2">{{ $errors->first("translate.$lang.description")  }}</div>
                  @endif
                </div>
              </div>
            @endforeach
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label" for="basic-default-name">видео</label>
              <div class="col-sm-10">
                <input type="file" class="form-control" name="video" value="{{old('video')}}" id="description" placeholder="видео" accept="video/*"/>

                @if($errors->has("video"))
                  <div class="text-danger mt-2">{{ $errors->first("video")  }}</div>
                @endif

              </div>

            </div>


            <div class="row justify-content-end">
              <div class="col-sm-10">
                <button type="submit" class="btn btn-primary">Отправлять</button>
              </div>
            </div>
          </form>
        </div>
  
    </div>
  </div>

</div>
<script>

  let getCourse = document.getElementById('course_language')


  getCourse.addEventListener('change', async event =>{

  let url ='/task/get-course-lessons?course_language='+getCourse.value

  try {
    const res = await fetch(url);

    const resData = await res.json();

    console.log(resData.data);

    let lessonSection = document.getElementById('lessons_section')
    lessonSection.innerHTML=''
    const opt = document.createElement('option')
    if(resData.data.length==0){
      const opt = document.createElement('option')
      opt.innerText ="Нет записей"
    }
    if(resData.data.length>0){
        resData.data.map(el => {
        const opt = document.createElement('option')
        opt.innerText = el.title
        opt.setAttribute('value',el.id)
        lessonSection.append(opt)
      })

    }
  } catch (err) {
    console.log(err.message);
  }
})
</script>

@endsection
