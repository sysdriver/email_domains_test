<?php

require 'Db.php';
require 'User.php';
require 'functions.php';

//list($name, $gender, $email) = ['user1',1,'blabla@mail.com'];   //TEST GOOD
//list($name, $gender, $email) = ['user1',1,''];    //TEST BAD
list($name, $gender, $email) = generateUserData();
new User($name, $gender, $email);

list($host, $username, $password, $database) = include 'db_config.php';
$db = new Db($host, $username, $password, $database);
print_r($db);

User::setDb($db);
echo memory_get_usage() . PHP_EOL;

for ($k=0;$k<1000;$k++) {
    $users = [];
    for ($i=0;$i<100000;$i++) {
        list($name, $gender, $email) = generateUserData();
        $users[] = new User($name, $gender, $email);
        ;
    }

    User::bulkSave($users);
    echo memory_get_usage(true) . PHP_EOL;
}

//print_r($users);
//$user->save();
