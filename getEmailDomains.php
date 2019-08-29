<?php

require 'Db.php';
require 'User.php';
require 'functions.php';

$port = 3306;
list($host, $username, $password, $database) = include 'db_config.php';
$db = new PDO('mysql:host=' . $host . ':' . $port . ';dbname=' . $database, $username, $password);

$sqlCnt = "SELECT count(*) FROM `users`;";
$stmt = $db->prepare($sqlCnt);
$stmt ->execute();
$row = $stmt->fetch();

$total = $row[0];
$pos = 0;
$chunkSize = 100000;
$domains =[];

echo 'Memory usage ' . memory_get_usage(true) . PHP_EOL;

while ($pos < $total) {
    $query = "SELECT `email` FROM `users` LIMIT %d,%d";
    $query = sprintf($query, $pos, $chunkSize);
    $pos += $chunkSize;

    $stmt = $db->prepare($query);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $emails = $row['email'];

        if (strpos($emails, ',') !== false) {
            $mailArr = explode(',', $emails);
        } else {
            $mailArr = [$emails];
        }

        foreach ($mailArr as $k => $email) {
            if (!isset($domains[getDomain($email)])) {
                $domains[getDomain($email)] = 0;
            }
            $domains[getDomain($email)] +=1;
        }
    }
    echo 'Processed ' . $chunkSize . ' records, position ' . $pos . PHP_EOL;
    echo 'Memory usage ' . memory_get_usage(true) . PHP_EOL;
}

print_r($domains);

/*
Array
(
    [mail.com] => 7
    [hotmail.it] => 7
    [somemail.com] => 3
    [othermail.com] => 7
    [twitter.com] => 5
    [hotmail.com] => 6
    [yahoo.it] => 10
    [instagram.com] => 7
    [simple.com] => 6
    [live.co.uk] => 8
    [yahoo.com] => 7
    [free.fr] => 9
    [gmail.com] => 5
    [aol.com] => 7
    [rambler.ru] => 6
    [test.ru] => 7
    [mail.ru] => 8
    [facebook.com] => 5
    [vk.com] => 5
    [yandex.ru] => 6
)
*/
