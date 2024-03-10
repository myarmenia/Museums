<?php

namespace App;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class FirebaseToken
{
    public static function decode($token)
    {
        $key = '85ldofi';
        // $publicKeys = [
        //     'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA',
        //     '-----BEGIN PUBLIC KEY-----',
        //     '-----END PUBLIC KEY-----',
        //     'Y3JhbG9yLmdldm9yZy45N0BnbWFpbC5jb20iLCJlbWFpbF92ZXJpZmllZCI6dHJ1ZSwibmJmIjoxNzA4OTI3NDI1LCJuYW1lIjoiR2V2b3JnIEJhZ2hkYXNhcnlhbiIsInBpY3R1cmUiOiJodHRwczovL2xoMy5nb29nbGV1c2VyY29udGVudC5jb20vYS9BQ2c4b2NJdUJrczBzbDEwQldnaVdIcTd0QmE0MFVONkRxRFZKeVdCeG91SXl2cE1QQT1zOTYtYyIsImdpdmVuX25hbWUiOiJHZXZvcmciLCJmYW1pbHlfbmFtZSI6IkJhZ2hkYXNhcnlhbiIsImxvY2FsZSI6InJ1IiwiaWF0IjoxNzA4OTI3NzI1LCJleHAiOjE3MDg5MzEzMjUsImp0aSI6IjM2ZWViMDIxZWVhNDBjNDc1ODQxYjgzMmU4OWQ2NGJhMzNiY2M0MzgifQ.kND1phmAubqh__YoEjcXoBw-dupAWLlWkMOQzM7EyTHTcroHmZ1nSw_vr0MH84XbE4IjR1krkTo-7g8L8MvZW8L57CeXhWJsrkJpM5u-CRylspKd_A-Rs2w3Kvrd2dRZMUtJMZCEeLgtO5FEaOqKp6ciST8HKVLzkpn_8YyOAd8aznnJlmvoHGXBeiOvjS6kuBFWpgawYJZF61bq2fpJ9mslCw-4A6LGEJP1E8PnSFoZ6QCOShaDTywn-teTzmSZ24INm0E3O6lWaiptGjUQS13ZZdO4_Qxez7FdfK3sJL4it5G9PW_sPSNO3-2n7Z83-z5vBQxoFUTw98Q'

        // ];
        

        return JWT::decode($token, new Key($key, 'HS256'));
    }
}