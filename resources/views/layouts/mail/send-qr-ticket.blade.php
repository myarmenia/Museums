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
          justify-content: center; gap: 40px;
      }
      .card-div-first-child{
        width: 60%;
        padding: 20px;
      }
      .card-div-last-child{
        width: 40%;
      }
    </style>
</head>

<body style="width: 100%; background-color: #f8f8f8">

    <div style="width: 60%; margin: 0 auto">
        <section style="background: rgb(227, 226, 224); padding: 30px 40px">
            <img src="{{$message->embed('assets/img/logos/museum-logo.png')}}" alt="museum-log" id="logo">

            <div style="text-align: center; align-item: center">
                <img style="width: 54px; height: 54px;" src="{{ $message->embed('assets/img/logos/hh.png')}}" alt="HH-Zinanshan">
                <p style="margin: 12px auto; width: 400px; text-align: center;">ՀԱՅԱՍՏԱՆԻ ՀԱՆՐԱՊԵՏՈՒԹՅԱՆ ԿՐԹՈՒԹՅԱՆ, ԳԻՏՈՒԹՅԱՆ, ՄՇԱԿՈՒՅԹԻ ԵՎ ՍՊՈՐՏԻ
                    ՆԱԽԱՐԱՐՈՒԹՅՈՒՆ</p>
            </div>

            <div class="ticket-card">
                <div class="card-div" >
                    <div class="card-div-first-child">
                        <div style="display: flex; align-items: center;">
                            <img style="width: 33px; height: 43px;" src="{{ $message->embed('assets/img/logos/logo.png') }}" alt="logo">
                            <p style="text-transform: uppercase; font-size: 10px; font-weight: 400; line-height: 13.6px; border-bottom: 1px solid black; padding-bottom: 5px;">անվճար տոմս   free ticket   бесплатный билет </p>
                        </div>

                        <div style="font-size: 10px; ine-height: 13.6px; margin-top: 6px; margin-left: 6px;">
                            <p style="font-weight: 600">Մարտիրոս Սարյանի թանգարան, Երևան</p>
                            <span>
                                <svg width="12" height="12" viewBox="0 0 6 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 0C1.49952 0 0.283203 1.21655 0.283203 2.71728C0.283203 4.21778 2.69995 8 3 8C3.3003 8 5.7168 4.21777 5.7168 2.71728C5.7168 1.21655 4.50048 0 3 0ZM3 3.75634C2.42627 3.75634 1.96094 3.29102 1.96094 2.71728C1.96094 2.14306 2.42627 1.67773 3 1.67773C3.57373 1.67773 4.03931 2.14306 4.03931 2.71728C4.03931 3.29102 3.57373 3.75634 3 3.75634Z" fill="#B26705"/>
                                    </svg>

                                Մարտիրոս Սարյան փ․, 3. Երևան 375002, Հայաստան
                            </span>
                        </div>

                        <div style="font-size: 10px; line-height: 13.6px; margin-top: 6px; margin-left: 6px;">
                            <p style="font-weight: 600">Музей Мартироса Сарьяна, Ереван</p>
                            <span>
                                <svg width="12" height="12" viewBox="0 0 6 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 0C1.49952 0 0.283203 1.21655 0.283203 2.71728C0.283203 4.21778 2.69995 8 3 8C3.3003 8 5.7168 4.21777 5.7168 2.71728C5.7168 1.21655 4.50048 0 3 0ZM3 3.75634C2.42627 3.75634 1.96094 3.29102 1.96094 2.71728C1.96094 2.14306 2.42627 1.67773 3 1.67773C3.57373 1.67773 4.03931 2.14306 4.03931 2.71728C4.03931 3.29102 3.57373 3.75634 3 3.75634Z" fill="#B26705"/>
                                    </svg>

                                    ул. Мартироса Сарьяна, 3. Ереван 375002, Армения
                            </span>
                        </div>

                        <div style="font-size: 10px; line-height: 13.6px; margin-top: 6px; margin-left: 6px;">
                            <p style="font-weight: 600">Martiros Sarian's Museum, Yerevan </p>
                            <span>
                                <svg width="12" height="12" viewBox="0 0 6 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 0C1.49952 0 0.283203 1.21655 0.283203 2.71728C0.283203 4.21778 2.69995 8 3 8C3.3003 8 5.7168 4.21777 5.7168 2.71728C5.7168 1.21655 4.50048 0 3 0ZM3 3.75634C2.42627 3.75634 1.96094 3.29102 1.96094 2.71728C1.96094 2.14306 2.42627 1.67773 3 1.67773C3.57373 1.67773 4.03931 2.14306 4.03931 2.71728C4.03931 3.29102 3.57373 3.75634 3 3.75634Z" fill="#B26705"/>
                                    </svg>

                                    3 Martiros Sarian St.  Yerevan 375002, Armenia 
                            </span>
                        </div>

                        <div>
                            <h3>0 AMD</h3>
                        </div>
                    </div>
                    <div class="card-div-last-child" style="background-color: #16EA91">
                        <img  style="padding: 30px;" src="{{$message->embed(Storage::disk('local')->path($data->path))}}" alt="qr">
                    </div>
                </div>




            </div>
        </section>
      </div>
</body>

</html>
