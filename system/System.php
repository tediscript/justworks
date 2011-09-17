<?php

class System {
    
    public static $conf = array();
    
    public static function init($config){
        self::$conf = $config;
        
        require_once(self::$conf["application"] . "config/config.php");
        require_once(self::$conf["system"] . "Controller.php");
        require_once(self::$conf["system"] . "URI.php");
        require_once(self::$conf["system"] . "JustWorksString.php");       
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
        //print_r($command); exit();
        $filteredUri =  implode("/", $command);
        
        //routing to controller
        $controllerName = "";        
        if(empty($filteredUri)){
            $controllerName = $config["application"]["default_controller"];
        } else if(array_key_exists($filteredUri, $config["routing"])){
            $controllerName = $config["routing"][$filteredUri];
        } else {
            //route with parametric uri
            $string = new JustWorksString();
            $newRouting = array();
            foreach($config["routing"] as $rk => $rv){
                $arrk = explode("{", $rk);
                ($string->endsWith("/", $arrk[0])) ? $kr = substr($arrk[0], 0, -1) : $kr = $arrk[0];
                $newRouting[$kr] = $rv;
            }
            //sort from the longest keyRouting
            $keyRouting = array();
            foreach($newRouting as $nrk => $nrv){
                $keyRouting[] = $nrk;
            }
            for($k=0; $k<sizeof($keyRouting); $k++){
                for($l=0; $l<sizeof($keyRouting); $l++){
                    if(strlen($keyRouting[$l]) < strlen($keyRouting[$k])){
                        $krTemp = $keyRouting[$l];
                        $keyRouting[$l] = $keyRouting[$k];
                        $keyRouting[$k] = $krTemp;
                    }
                }
            }
            $sortedRouting = array();
            $originalKeyArray = array();
            $originalKey = "";
            foreach($keyRouting as $nri){
                if($string->startsWith($nri, $filteredUri)){
                    $controllerName = $newRouting[$nri];
                    $originalKeyArray = array_keys($config["routing"], $controllerName);
                    $originalKey = $originalKeyArray[0];
                    break;   
                }
            }
            //convert uri to GET parameter
            $arrKey = explode("/", $originalKey);
            $resmap = array();
            foreach($arrKey as $ar){
                if($string->startsWith("{", $ar) && $string->endsWith("}", $ar)){
                    $resmap[] = substr($ar, 1, -1);
                } else {
                    $resmap[] = "";
                }
            }
            $arrVal = explode("/", $filteredUri);
            for($m=0; $m<sizeof($resmap); $m++){
                if($resmap[$m] != ""){
                    isset($arrVal[$m]) ? $_GET[$resmap[$m]] = $arrVal[$m] : $_GET[$resmap[$m]] = "";
                }
            }
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
