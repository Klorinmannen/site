<?php
ob_clean();
$request = \api\request::parse();
$controller = array_shift($request);

try {
    
    // Does the requested controller exist?
    $api_controller = sprintf('%s\api\controller', $controller);
    if (!class_exists($api_controller))
        throw new \Exception('Bad request, endpoint does not exist', 400);    
    
    // Map request to an defined endpoint if there is one
    $uri = \api\request::parse_uri();
    $endpoint = \api\router::map($_SERVER['REQUEST_METHOD'], $controller, $uri);

    // Define the endpoint call
    $api_endpoint = sprintf('%s\api\controller::%s', $controller, $endpoint);

    // Make the actual endpoint call
    $json_encoded_response_data = $api_endpoint( ...$request);

    // Return response data and set response code
    echo $json_encoded_response_data;   
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
    exit;
}
    

