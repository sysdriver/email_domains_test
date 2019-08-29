<?php

function getDomain($email)
{
    if (!checkEmail($email)) {
        return FALSE;
    }

    $parts = explode("@", $email);

    return $parts[1];
}

function generateUserData()
{
    $name = generateRandomLogin();
    $emailCnt = rand(1,5);
    //consider multiple emails
    $email = '';
    for ($i=0;$i<$emailCnt;$i++) {
        $email .= generateRandomMail($name).',';
    }

    $email = substr($email,0,-1);
    $gender = rand(1, 2);

    return [$name, $gender, $email];
}

function generateRandomMail($login='')
{
    $domains = [
        'mail.com',
        'gmail.com',
        'mail.ru',
        'yandex.ru',
        'rambler.ru',
        'yahoo.com',
        'test.ru',
        'hotmail.com',
        'somemail.com',
        'othermail.com',
        'simple.com',
        'facebook.com',
        'twitter.com',
        'instagram.com',
        'vk.com',
        'aol.com',
        'hotmail.it',
        'yahoo.it',
        'live.co.uk',
        'free.fr'
    ];

    if (empty($login)) {
        $login = generateRandomLogin();
    }

    return $login . '@' . $domains[rand(0, (count($domains)-1))];
}

function generateRandomLogin($length = 10)
{
    $characters = 'abcdefghijklmnopqrstuvwxyz';
    $login = $characters[rand(0, (strlen($characters)-1))];

    if (empty($length)) {
        $length = rand(2, 9);
    }

    $login .= generateRandomString($length);

    return $login;
}

function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function checkEmail($email)
{
    $find1 = strpos($email, '@');
    $find2 = strpos($email, '.');
    return ($find1 !== false && $find2 !== false && $find2 > $find1);
}
