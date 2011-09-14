<?php



//route url patter into Controller
$config["routing"] = array(
    "a/b/sasa/v" => "Welcome"
);

//auto load external libraries or models
$config["autoload"] = array(
    "application.libraries.JSONResponse"
);

//default System configuration
$config["application"] = array(
    "default_controller" => "Welcome",
    "permitted_uri_chars" => ""
);
