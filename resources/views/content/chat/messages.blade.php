@extends('layouts/contentNavbarLayout')

@section('title', 'Հաղորդագրություն')
@section('page-script')
    <script src="{{ asset('assets/js/admin/chat/send-message.js') }}"></script>
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/chat/chat.css') }}">
@endsection

@section('content')
    @include('includes.alert')
    @if ($data->deleted_at)
        <h2 class="text-muted fw-light pb-3" style="color:red">Տվյալ նամակագրություն ջնջված է</h2>
    @elseif ($data->visitor_id && is_null($data->visitor))
        <h2 class="text-muted fw-light pb-3" style="color:red">Այցելուի հաշիվը ջնջված է</h2>
    @endif

    @if (!($data->visitor_id && is_null($data->visitor)))
      <h4 class="py-3 mb-4">
          <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                  <li class="breadcrumb-item">
                      Նամակագրություն {{ $data->email ?? ($data->visitor->email ?? '') }} -ի հետ
                  </li>
              </ol>
          </nav>
      </h4>

    @endif
    <div class="">
        @if ($data->email && !$data->visitor)
            <h5 class="card-title"><strong>Այս հաղորդագրությունը ուղարկվել է չգրանցված այցելուի կողմից, այդ
                    պատճառով պատասխանը կուղարկվի նրա էլ․ հասցեին</strong></h5>
        @endif
    </div>

    <input type="hidden" id="chat_id" value="{{ $data->id }}">
    {{-- @dd($data); --}}
    <div class="{{ $data->visitor_name || $data->visitor_phone ? 'mx-3 my-2' : '' }}">
            @if ($data->visitor_name)
                <div>
                    <span class="text-muted fw-bold">Անուն: </span><span class="text-muted fw-light">{{ $data->visitor_name }} </span>
                </div>
            @endif
            @if ($data->visitor_phone)
                <div>
                    <span class="text-muted fw-bold">Հեռախոսահամար: </span><span class="text-muted fw-light">{{ $data->visitor_phone }} </span>
                </div>
            @endif
    </div>
    <div class="card">
        <div class="page-content page-container" id="page-content">
            <div class="d-flex justify-content-center w-100">
                <div class="w-100">


                    <div class="ps-container ps-theme-default ps-active-y p-4" id="chat-content"
                        style="overflow-y: scroll !important; height:400px !important;">
                        @foreach ($data->messages as $message)
                            <div class="{{ $message->type == 'visitor' ? 'media media-chat' : 'media media-chat-reverse' }}">
                                <div class="media-body">
                                    <p>{{ $message->text }}</p>
                                    <p class="meta"><time datetime="2018">{{ $message->created_at }}</time></p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if(
                        is_null($data->deleted_at) &&
                        ((!isset($data->visitor) && $data->email) || ($data->visitor_id && $data->visitor))
                    )
                        <div class="card publisher d-flex justify-content-between flex-row py-3 px-4">
                            <img class="avatar avatar-xs" src="https://img.icons8.com/color/36/000000/administrator-male.png" alt="...">
                            <input class="publisher-input form-control w-75" type="text" placeholder="Նամակ">
                            <div class="spinner-border text-primary" id="loader-message" style="display: none" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <button type="button" id="send-message" class="add-message btn btn-primary">Ուղարկել</button>
                            @if (!isSuperAdmin())
                                <a href="{{ route('educational_programs_calendar') }}" target="_blank">
                                    <i class="btn btn-primary tf-icons bx bx-table me-1"></i><span class="d-none d-sm-block"></span>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
<x-modal-delete></x-modal-delete>
