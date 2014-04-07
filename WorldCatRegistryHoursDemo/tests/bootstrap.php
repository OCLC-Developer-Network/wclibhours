<?php
error_reporting(E_ALL | E_STRICT);
require '../vendor/autoload.php';

//load yaml file
global $config;
$config = Yaml::parse('../app/config/config.yaml');
?>