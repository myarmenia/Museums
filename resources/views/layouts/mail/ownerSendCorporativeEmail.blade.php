<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Կորպորատիվ տոմսի գնում</title>
</head>

<body style="margin: 0; padding: 0; background-color: #f9f9f9;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td align="center" style="padding: 20px;">
                <table width="500px" cellpadding="0" cellspacing="0" border="0" style="max-width: 500px; background-color: white;">
                    <tr>
                        <td style="padding: 20px;">
                            <img src="https://manage.museumsarmenia.am/assets/img/mail-img/mobile-museum-logo.png" alt="Museum Logo" style="width: 130px; height: auto;">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 20px; text-align: center;">
                            <img src="https://manage.museumsarmenia.am/assets/img/mail-img/hh.png" alt="HH-Zinanshan" style="width: 54px; height: 54px;">
                            <p>ՀԱՅԱՍՏԱՆԻ ՀԱՆՐԱՊԵՏՈՒԹՅԱՆ ԿՐԹՈՒԹՅԱՆ, ԳԻՏՈՒԹՅԱՆ, ՄՇԱԿՈՒՅԹԻ ԵՎ ՍՊՈՐՏԻ ՆԱԽԱՐԱՐՈՒԹՅՈՒՆ</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 20px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="padding-bottom: 10px; border-bottom: 1px solid #c4c4c4;">
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td><p>Թանգարան</p></td>
                                                <td align="right"><p>{{ $data['museum_name'] }}</p></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom: 10px; border-bottom: 1px solid #c4c4c4;">
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td><p>Գնման ամսաթիվ</p></td>
                                                <td align="right"><p>{{ $data['date'] }}</p></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom: 10px; border-bottom: 1px solid #c4c4c4;">
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td><p>Գեներացված տոմսեր</p></td>
                                                <td align="right"><p>{{ $data['ticketCount'] }}</p></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom: 10px; border-bottom: 1px solid #c4c4c4;">
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td><p>Մնացորդ</p></td>
                                                <td align="right"><p>{{ $data['remainder'] }}</p></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
