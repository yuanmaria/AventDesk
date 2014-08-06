<?php

require_once "../vendor/autoload.php";
require_once "../app/Avent/InitApplication.php";
require_once "../app/Avent/Application.php";

define("ENV", "dev");
define("APP_PATH", "../app/");

$app = new \App\Avent\Application(new Phalcon\DI\FactoryDefault());

echo $app->run();
