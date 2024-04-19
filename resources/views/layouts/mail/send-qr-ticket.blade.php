<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>

      body{
        color: black
      }
      .cont{
        width: 50%;
        margin: 0 auto
      }
      #logo{
        width: 18%;
      }
      .ticket-card{
        margin-top: 30px;
        color: black

      }
      .card-div{
        display: flex;
        background-color: white;
        align-items: center ;
        flex-wrap: wrap;
        justify-content: center;
        gap: 40px;
      }

      .card-div-first-child{
        width: 60%;
        padding: 20px;
      }

      .card-div-last-child{
        width: 40%;
        -webkit-align-items: center;
        -ms-align-items: center;
        align-items: center !important;
        border-left: dashed 2px #4b4747;
      }

      #qr_img_cont{
          display: block;
          margin: auto;
          height:100%;
          display: -webkit-flex;
          -webkit-align-items: center;
          -webkit-justify-content: center;
          -webkit-margin: 0 auto;
          -moz-margin: 0 auto;
          -ms-margin: 0 auto;
          -o-margin: 0 auto;
      }

      #qr_img{
        display: block;
        width: 70%;
        margin: auto;
        -webkit-margin: 0 auto;
        -moz-margin: 0 auto;
        -ms-margin: 0 auto;
        -o-margin: 0 auto;
      }

      .qr_responsive{
         width: 66% !important;
      }
      #price_cont{
        margin-top: 20px;
        margin-left: 6px;
        font-size: 12px;
        display: flex;
        -webkit-justify-content: space-between;
        justify-content: space-between !important
      }
      #price_cont span:first-child{
        font-size: 18px;

      }

      @media (max-width: 1024px) {
        .cont {
          width: 100%;
        }
        .card-div{
          display: ruby-text !important;
          background-color: white !important;
          -webkit-align-items: center;
          -ms-align-items: center;
          flex-direction: column-reverse !important;
          justify-content: center !important;
        }
        .card-div-first-child{
          width: 90%;
        }
        .card-div-last-child {
          width: 100%;
          border-top: dashed 1px #aaa;
          border-left: unset;
          align-items: center;
        }
        #qr_img_cont{
          margin: auto;
          height:80%;
          display: flex;
          display: block;
          display: -webkit-flex;
          -webkit-align-items: center;
          -webkit-justify-content: center;
          -webkit-margin: auto;
          -moz-margin: auto;
          -ms-margin: auto;
          -o-margin: auto;
        }

        #price_cont{
          font-size: 8px
        }

        #price_cont span{
          font-size: 10px
        }
        #qr_img{
          width: 46%;
          margin: 20px auto;
        }
      }



    </style>
</head>

<body >

    <div class="cont" >
        <section style="background: #e3e2e059; padding: 30px 40px">
            <img src="{{$message->embed('assets/img/logos/museum-logo.png')}}" alt="museum-log" id="logo">

            <div style="text-align: center; align-item: center">
                <p style="margin: 12px auto; font-weight: 600; text-align: center; width: 60%">ՀԱՅԱՍՏԱՆԻ ՀԱՆՐԱՊԵՏՈՒԹՅԱՆ ԿՐԹՈՒԹՅԱՆ, ԳԻՏՈՒԹՅԱՆ, ՄՇԱԿՈՒՅԹԻ ԵՎ ՍՊՈՐՏԻ
                    ՆԱԽԱՐԱՐՈՒԹՅՈՒՆ</p>
            </div>
            @foreach ($result as $data)
                @php
                    $type = $data->purchased_item->type;
                @endphp
                <div class="ticket-card">
                    <div class="card-div" >
                        <div class="card-div-first-child">
                            <div style="display: flex; align-items: center;">
                                <img style="width: 33px; height: 43px;" src="{{ $message->embed('assets/img/logos/logo.png') }}" alt="logo">
                                <p style="text-transform: uppercase; font-size: 12px; font-weight: 600; line-height: 13.6px; border-bottom: 1px solid black; padding-bottom: 5px;">{{ticketTitles()[$type]}}</p>
                            </div>
                            @if ($type == 'united')
                                  @php
                                      $united_museums = $data->purchased_item->united_museums();
                                  @endphp
                                  @if (count($united_museums) > 0)
                                      @foreach ($united_museums as $museum)
                                          <p style="font-weight: 600; margin: 10px 0">{{$museum->translation('en')->name}}</p>
                                      @endforeach
                                  @endif
                            @else
                                @foreach (languages() as $lng)
                                    <div style="font-size: 12px; ine-height: 13.6px; margin-top: 6px; margin-left: 6px;">
                                        <p style="font-weight: 600; margin-bottom: 0">{{$data->museum->translation($lng)->name}}</p>
                                        <span  style="font-size: 10px">
                                            <img src="{{ $message->embed('assets/img/icons/address.png') }}" >
                                            {{$data->museum->translation($lng)->address}}
                                        </span>
                                    </div>
                                @endforeach
                            @endif

                            <div id="price_cont">
                                <div style="width: 50%">
                                  <span style="font-weight: 600">{{$data->price}} </span>
                                  <span >AMD</span>
                                </div>
                                <div style="width: 50%; text-align: end; align-content: content">
                                    <span>{{ $data->created_at != null && ($type == 'subsctiption' || $type == 'united') ? date('d-m-Y', strtotime($data->created_at)) : ''}} </span>
                                    <span>{{ $type == 'event' ? date('d-m-Y', strtotime($data->purchased_item->event_config->day)) : ''}}</span>
                                    <span>{{ $type == 'event' ? date('H:i', strtotime($data->purchased_item->event_config->start_tyme)) : ''}}</span>
                                    <p style="margin-bottom: 0; font-size: 8px; color: #aaa">10047005</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-div-last-child" style="background: {{ticketColors()[$type]}}">

                            <div id="qr_img_cont" >
                                <div id="qr_img" class="{{ $type == 'united' ? 'qr_responsive' : ''}}">
                                  <img style="width: 100%" src="{{$message->embed(Storage::disk('local')->path($data->path))}}" alt="qr" />
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            @endforeach

        </section>
      </div>
</body>

</html>
