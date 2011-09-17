<?php



//route url patter into Controller
$config["routing"] = array(
    "profile/{username}/id/{userId}" => "Welcome",
    "profile/admin" => "Admin",
    "user/{config}" => "Config"
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
