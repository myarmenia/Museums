<?php

namespace App;

use Firebase\JWT\JWT;

class FirebaseToken
{
    public static function decode($token)
    {
        $publicKeys = [
            '-----BEGIN PUBLIC KEY-----',
            '-----END PUBLIC KEY-----',
        ]; // Замените плейсхолдеры реальными публичными ключами Google

        return JWT::decode($token, $publicKeys, ['RS256']);
    }
}