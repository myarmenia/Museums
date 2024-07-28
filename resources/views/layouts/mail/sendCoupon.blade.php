<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Բարի գալուստ Թանգարանի կայք</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f9f9f9;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td align="center" style="padding: 20px;">
                <table width="500px" cellpadding="0" cellspacing="0" border="0" style="max-width: 500px; background-color: white;">
                    <tr>
                        <td style="padding: 20px;">
                            <img src="https://museum.gorc-ka.am/assets/img/mail-img/mobile-museum-logo.png" alt="Museum Logo" style="width: 130px; height: auto;">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 20px; text-align: center;">
                            <img src="https://museum.gorc-ka.am/assets/img/mail-img/hh.png" alt="HH-Zinanshan" style="width: 54px; height: 54px;">
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
                                                <td><p>Կազմակերպություն</p></td>
                                                <td align="right"><p>{{$data['company_name']}}</p></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom: 10px; border-bottom: 1px solid #c4c4c4;">
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td><p>Կուպոն</p></td>
                                                <td align="right"><p>{{$data['coupon']}}</p></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom: 10px; border-bottom: 1px solid #c4c4c4;">
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td><p>Թանգարան</p></td>
                                                <td align="right"><p>{{$data['museum_name']}}</p></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom: 10px; border-bottom: 1px solid #c4c4c4;">
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td><p>Ուժի մեջ է մինչև</p></td>
                                                <td align="right"><p>{{ Carbon\Carbon::parse($data['ttl_at'])->format('d-m-Y') }}
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom: 10px; border-bottom: 1px solid #c4c4c4;">
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td><p>Տոմսերի քանակ</p></td>
                                                <td align="right"><p>{{$data['tickets_count']}}</p></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center; padding: 20px;">
                            <p>Թանգարան այցելելիս ներկայացրեք կուպոնը՝ տոմսերը ստանալու համար։</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>