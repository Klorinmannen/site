<?php
ob_clean();
$request = \api\request::parse();
$controller = array_shift($request);
$endpoint = \api\router::map($_SERVER['REQUEST_METHOD'], $controller, \api\request::parse_uri());
$api_endpoint = sprintf('%s\api::%s', $controller, $endpoint);

try {
    $api_endpoint( ...$request);
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
    

