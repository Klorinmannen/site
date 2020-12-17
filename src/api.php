<?php
ob_clean();

$request_path = array_filter(preg_split('/\//', $_SERVER['REQUEST_URI']));

try {
    if ($request_path[1] == 'api') {
        switch ($request_path[2]) {
        case 'pokemon':
            if ($_SERVER['REQUEST_METHOD'] == 'GET')
                \pokemon::get($request_path[3]);
            else
                throw new \Exception('invalid request method', 400);
            
            break;
        default:
            throw new \Exception('bad request', 400);
            break;        
        }
    
        http_response_code(200);
    }
} catch (\Exception $e) {

    switch ($e->getCode()) {
    case 500:
        echo 'internal server error';
        http_response_code(500);        
        break;
    case 400:
        echo $e->getMessage();
        http_response_code(400);
        break;
    }
}
    

