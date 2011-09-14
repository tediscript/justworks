<?php

$system = dirname(__FILE__).'/system/System.php';
$config["system"] = dirname(__FILE__).'/system/';
$config["application"] = dirname(__FILE__).'/application/';
$config["base_path"] = dirname(__FILE__) . "/";

require_once($system);
System::init($config);

