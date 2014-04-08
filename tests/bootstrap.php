<?php
error_reporting(E_ALL | E_STRICT);
require '../vendor/autoload.php';

//load yaml file
global $config;

use Symfony\Component\Yaml\Yaml;

$config = Yaml::parse('../app/config/config.yaml');
?>