<?php

require __DIR__ . "/../src/Payments.php";
require __DIR__ . "/../src/User.php";

$user =  new User();
$user->bootstrap(
    "Cleyber", 
    "Matos",
    "098980980"
);

$pay = new Payments($user, "ak_test_X3C8TFSQgiyW2gaVgSjNEXsAITA6aQ");

$pay->creditCard(
    "Cleyber Matos", 
    "5308 0842 4204 2820",
    "1122",
    "416"
);
