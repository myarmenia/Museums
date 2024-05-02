<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans;
        }

        .museum-name {
            font-size: 18px;
            font-weight: bold;
        }

        .text-margin {
            margin: 7px 0;
        }

        .ticket-info {
            font-size: 16px;
            display: flex;
            flex-direction: column;
        }

        .qr-code {
            width: 7cm;
            height: 7cm;
        }

        .pdf-tmp {
            margin-top: -40px;
            width: 100%;
        }

        .img {
            text-align: center;
            display: flex;
            justify-content: center;
        }

        .text-flex {
            display: flex;
        }
    </style>
</head>

<body>


    <div class="pdf-tmp">
        @foreach ($tickets['data'] as $item)
            <div class="img"><img src="{{ $item['photo'] }}" class="qr-code"></div>
            <span>{{ $item['ticket_token'] }}</span>
            <h4 class="museum-name text-margin">{{ $tickets['museum_name']['am'] }}</h4>
            <h4 class="museum-name text-margin">{{ $tickets['museum_name']['ru'] }}</h4>
            <h4 class="museum-name text-margin">{{ $tickets['museum_name']['en'] }}</h4>
            <div class="ticket-info">
                <div class="text-flex text-margin">
                    <span>Տեսակ - </span>
                    <span>&nbsp;{{ getTranslateTicketTitl($item['type']) }}</span>
                </div>
                @if ($item['description_educational_programming'])
                    <div class="text-flex text-margin">
                        <span>Անվանում - </span>
                        <span>{{ $item['description_educational_programming'] }}</span>
                    </div>
                @endif

                <div class="text-flex text-margin">
                    <span>Գին - </span>
                    <span>{{ $item['price'] }}դր․</span>
                </div>

                @if ($item['guid'])
                    @foreach ($item['guid'] as $price)
                        <div class="text-flex text-margin">
                            <span>էքսկուրսավար - </span>
                            <span>{{ $price }}</span>
                        </div>
                    @endforeach
                @endif
                <div class="text-flex text-margin">
                    <span>Ստեղծվել է - </span>
                    <span>{{ $item['created_at'] }}</span>
                </div>
            </div>
    </div>
    @endforeach

</body>

</html>
