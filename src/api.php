<?php
ob_clean();
$uri = preg_replace('/\/api\//', '', $_SERVER['REQUEST_URI']);
$request_path = preg_split('/\//', $uri);

try {
    switch ($request_path[0]) {
    case 'pokemon':
        if ($_SERVER['REQUEST_METHOD'] == 'GET')            
            if (isset($request_path[1]))
                \pokemon\api::get($request_path[1]);
            else
                \pokemon\api::get_list();
        else
            throw new \Exception('invalid request method', 400);
            
        break;
    default:
        throw new \Exception('bad request', 400);
        break;        
    }
    
    http_response_code(200);
        
} catch (\Exception $e) {

    switch ($e->getCode()) {
    case 500:
        http_response_code(500);        
        break;
    case 400:
        echo $e->getMessage();
        http_response_code(400);
        break;
    }
}
    

