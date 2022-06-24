<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use MySql\MVC\Controllers\Controller;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$loader = new FilesystemLoader(dirname(__DIR__) . '/src/MySql/MVC/View');
$twig = new Environment($loader);
$controller = new Controller($twig);

$controller->action();