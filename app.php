<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Command\ValidateMail;
use Symfony\Component\Console\Application;

$app = new Application();
$app->add(new ValidateMail());
$app->run();
