<?php

//System::import("application.libraries.JSONResponse");

class Welcome extends Controller {
    
    public function init(){
        $response = new JSONResponse();
        $data["nama"] = "barjo";
        $response->ok($data);
    }
    
}
