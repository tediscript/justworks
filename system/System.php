<?php

class System {
    
    public static $conf = array();
    
    public static function init($config){
        self::$conf = $config;
        
        require_once(self::$conf["application"] . "config/config.php");
        require_once(self::$conf["system"] . "Controller.php");
        require_once(self::$conf["system"] . "URI.php");        
        //get url
        $uri = new URI();
        $request_uri = $uri->filterUri($_SERVER['REQUEST_URI'], $config["application"]);
        $script_name = $uri->filterUri($_SERVER['SCRIPT_NAME'], $config["application"]);
        $ru = explode('?', $request_uri);
        $sn = explode('?', $script_name);
        $requestURI = explode('/', $ru[0]);
        $scriptName = explode('/', $sn[0]);

        for($i= 0;$i < sizeof($scriptName);$i++){
            if ($requestURI[$i] == $scriptName[$i]){
                unset($requestURI[$i]);
            }
        }
        $command = array_values($requestURI);
        $filteredUri =  implode("/", $command);
        
        //routing
        if(empty($filteredUri)){
            $controllerName = $config["application"]["default_controller"];
        } else if(array_key_exists($uri, $config["routing"])){
            $controllerName = $config["routing"][$uri];
        } else {
            $controllerName = "";    
        }
        
        //initialize controller
        $path = self::$conf["application"] . "controllers/" . $controllerName . ".php";
        if(file_exists($path)){
           require_once($path);
           //crate instance
           $controller = new $controllerName();
           
           //load external (libraries, models, etc)
            foreach($config["autoload"] as $item){
                self::import($item);
                $arrpath = explode(".", $item);
                $className = end($arrpath);
                $memberName = lcfirst($className);
                $controller->$memberName = new $className();
            }
           
           //invoke init
           $controller->init();
        } else {
            self::notFound();
        }
    }
    
    public static function import($package){
        $path = self::$conf["base_path"] . str_replace(".", "/", $package) . ".php";
        require_once($path);
    }
    
    public static function instantiate($package){
        $arrpath = explode(".", $package);
        return new end($arrpath);
    }
    
    public static function notFound(){
        require_once(self::$conf["application"] . "controllers/NotFound.php");
        $controller = new NotFound();
        $controller->init();
    }
    
}
