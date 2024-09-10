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
            font-size: 14px;
            font-weight: bold;
        }
        .text-margin {
            margin: 7px 0;
        }
        .ticket-info {
            font-size: 13px;
            display: flex;
            flex-direction: column;
        }
        .qr-code {
            width: 7cm;
            height: 7cm;
        }
        .page-break {
                    page-break-after: always;
        }
        .pdf-tmp {
            width: 100%;
            height: 100vh;
            overflow:auto;

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

        @foreach ($tickets['data'] as $key=>$item)

            @if (isset($item['photo']))
                <div class="img"><img src="{{ $item['photo'] }}" class="qr-code"></div>
            @endif

            <span>{{ $item['ticket_token'] }}</span>
            <h4 class="museum-name text-margin">{{ $tickets['museum_name']['am'] }}</h4>
            <h4 class="museum-name text-margin">{{ $tickets['museum_name']['ru'] }}</h4>
            <h4 class="museum-name text-margin">{{ $tickets['museum_name']['en'] }}</h4>
            <div class="ticket-info {{ $key == count($tickets['data'])-1 ? '' : 'page-break' }}">
                <div class="text-flex text-margin">
                    <span>Տեսակ/Type - </span>
                    <span>&nbsp;{{ getTranslateTicketTitl($item['type']) }} /
                        {{ getTranslateTicketTitl_en($item['type']) }}</span>
                </div>
                @if (isset($item['sub_type']) &&
                        $item['sub_type'] != null &&
                        $item['sub_type'] != 'guide_price_am' &&
                        $item['sub_type'] != 'guide_price_other')
                    <div class="text-flex text-margin">
                        <span>Տոմսի Տեսակ/Ticket type - </span>
                        <span>&nbsp;{{ $item['sub_type'] == 'standart' ? 'Ստանդարտ/Standart' : ($item['sub_type'] == 'discount' ? 'Զեղչված/Discount' : 'Անվճար/Free') }}</span>
                    </div>
                @endif
                @if ($item['description_educational_programming'])
                    <div class="text-flex text-margin">
                        <span>Անվանում/Name - </span>
                        <span>{{ $item['description_educational_programming'] }} /
                            {{ $item['description_educational_programming_en'] }} </span>
                    </div>
                @endif
                @if ($item['type'] == 'event')
                    <div class="text-flex text-margin">
                        <span>Վավեր է․/Valid time </span>
                        <span>{{ $item['action_date']['start'] }} - {{ $item['action_date']['end'] }}</span>
                    </div>
                @endif

                @if ($item['type'] == 'event-config')
                    <div class="text-flex text-margin">
                        <span>Անցկացման օր/Date - </span>
                        <span>{{ $item['action_date']['day'] }}</span>
                    </div>
                    <div class="text-flex text-margin">
                        <span>Ժամ/Time - </span>
                        <span>{{ $item['action_date']['start'] }} - {{ $item['action_date']['end'] }}</span>
                    </div>
                @endif

                @if (isset($item['price']))
                    @if (isset($item['photo']))
                        <div class="text-flex text-margin">
                            <span>Գին/Price - </span>
                            <span>{{ $item['price'] }}դր․/AMD</span>
                        </div>
                    @endif
                @endif

                @if ($item['guid'])
                    @foreach ($item['guid'] as $price)
                        <div class="text-flex text-margin">
                            <span>էքսկուրսավար/Guide - </span>
                            <span>{{ $price }}դր․/AMD</span>
                        </div>
                    @endforeach
                @endif
                @if ($item['type'] == 'guide')
                    <div class="text-flex text-margin">
                        <span>էքսկուրսավար/Guide - </span>
                        <span>{{ $item['price'] }}դր․/AMD</span>
                    </div>
                @endif
                <div class="text-flex text-margin">
                    <span>Ստեղծվել է/Created - </span>
                    <span>{{ $item['created_at'] }}</span>
                </div>
            </div>
    </div>
    @endforeach

</body>

</html>
